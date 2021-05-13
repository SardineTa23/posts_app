<?php
require_once 'model.php';
class Image extends Model
{
	function __construct()
	{
		parent::__construct();
	}

	function create($selected_images, $article_id)
	{

		$path = "/var/www/app/views/images/article_images/";

		// 今回の記事の画像を保存するディレクトリ作成
		mkdir($path . $article_id, 0777);



		foreach ($selected_images as $file_place => $image) {
			$statement = $this->db->prepare('INSERT INTO images SET article_id=?, url=?');
			try {

				// 未定義である・複数ファイルである・$_FILES Corruption 攻撃を受けた
				// どれかに該当していれば不正なパラメータとして処理する
				if (!isset($_FILES["$file_place"]['error']) || !is_int($_FILES["$file_place"]['error'])) {
					throw new RuntimeException('パラメータが不正です');
				}

				// $_FILES["$file_place"]['error'] の値を確認
				switch ($_FILES["$file_place"]['error']) {
					case UPLOAD_ERR_OK: // OK
						break;
					case UPLOAD_ERR_NO_FILE:   // ファイル未選択
						throw new RuntimeException('ファイルが選択されていません');
					default:
						throw new RuntimeException('その他のエラーが発生しました');
				}

				// $_FILES["$file_place"]['mime']の値はブラウザ側で偽装可能なので
				// MIMEタイプに対応する拡張子を自前で取得する
				if (!$ext = array_search(
					mime_content_type($_FILES["$file_place"]['tmp_name']),
					array(
						'gif' => 'image/gif',
						'jpg' => 'image/jpeg',
						'png' => 'image/png',
					),
					true
				)) {
					throw new RuntimeException('ファイル形式が不正です');
				}
				//まずファイルの存在を確認し、その後画像形式を確認する
				$image_name = $image['tmp_name'];

				// ファイルをサーバーに保存
				if (is_uploaded_file($_FILES["$file_place"]['tmp_name'])) {
					$extension = image_type_to_extension(exif_imagetype($_FILES["$file_place"]['tmp_name']));
					$file_name = date('YmdHis') . '-' . sha1_file($_FILES["$file_place"]['tmp_name']) . $extension;
					move_uploaded_file($_FILES["$file_place"]['tmp_name'], "/var/www/app/views/images/article_images/$article_id/" . $file_name);
					$statement->execute(array(
						$article_id,
						$file_name
					));

					// 配列の最初の要素のIDだけ取得する。
					if ($file_place === "image1") {
						$first_image_id = $this->db->lastInsertId();
					}
				}
			} catch (RuntimeException $e) {
				echo $e->getMessage();
				exit();
			}
		}
		return $first_image_id;
	}
}
