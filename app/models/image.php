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
		$statement = $this->db->prepare('INSERT INTO images SET article_id=?, url=?');
		$path = "/var/www/app/views/images/article_images/";

		// 今回の記事の画像を保存するディレクトリ作成
		mkdir($path . $article_id, 0777);

		foreach ($selected_images as $file_place => $image) {
			// 保存するファイル名に、日付を追加して重複を防ぐ
			$image = date('YmdHis') . $image['name'];
			// ファイルをサーバーに保存
			if (is_uploaded_file($_FILES["$file_place"]['tmp_name'])) {
				move_uploaded_file($_FILES["$file_place"]['tmp_name'], "/var/www/app/views/images/article_images/$article_id/" . $image);
				$statement->execute(array(
					$article_id,
					$image
				));

				// 配列の最初の要素のIDだけ取得する。
				if ($file_place === "image1") {
					$image_id = $this->db->lastInsertId();
				}
			}
		}
		return $image_id;
	}
}
