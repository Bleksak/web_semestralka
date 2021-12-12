<?php

namespace model;

class Article extends Model
{
	const TABLE = "articles";

	public function create($author, $title, $abstract, $filename)
	{
		$this->db->insert(
			self::TABLE,
			[
				"author" => $author,
				"title" => $title,
				"abstract" => $abstract,
				"file" => $filename
			]
		);
	}

	public function get($id)
	{
		$result = $this->db->join(
			self::TABLE,
			User::TABLE,
			"articles.author = users.id",
			["articles.id", "articles.author", "articles.title", "articles.abstract", "articles.date", "articles.file", "articles.approved", "users.firstname", "users.lastname"],
			"articles.id = ?",
			[$id]
		);

		return empty($result) ? false : $result[0];
	}

	public function getAllApproved()
	{
		return $this->db->join(
			"articles",
			"users",
			"articles.author = users.id",
			["articles.id", "articles.title", "articles.abstract", "articles.date", "articles.file", "users.firstname", "users.lastname"],
			"articles.approved = ?",
			[1]
		);
	}

	public function getFromAuthor($author)
	{
		return $this->db->select(self::TABLE, [], "author = ?", [$author]);
	}

	public function update($id, array $params)
	{
		return $this->db->update(
			"articles",
			$params,
			"id=?",
			array($id)
		);
	}

	public function search($name)
	{
		$name = mb_strtolower($name);

		return $this->db->join(
			self::TABLE,
			User::TABLE,
			"articles.author = users.id",
			["articles.author", "articles.id", "articles.title", "articles.abstract", "articles.date", "users.firstname", "users.lastname"],
			"LOWER(articles.title) LIKE ? AND articles.approved = ?",
			["%$name%", 1],
		);
	}

	public function getAll()
	{
		return $this->db->join(
			"articles",
			"users",
			"articles.author = users.id",
			["articles.id", "articles.title", "articles.abstract", "articles.file", "articles.date", "articles.approved", "users.firstname", "users.lastname"],
			"",
			[],
			"ORDER BY articles.approved"
		);
	}

	public function approve($id)
	{
		return $this->db->update("articles", ["approved" => true], "id=?", [$id]);
	}

	public function delete($id)
	{
		return $this->db->delete("articles", "id=?", [$id]);
	}
}
