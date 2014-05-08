<?php

namespace Storage;

use \Aws\S3\S3Client;

class Storage_AmazonS3 extends Storage_Driver
{
	protected $client;
	protected $bucket;
	
	public static function _init()
	{
		\Package::load('amazon');
	}
	
	public function __construct()
	{
		try {
			$amazon = \Amazon::instance();
		} catch (\AmazonS3Exception $e) {
			return;
		}
		
		$bucket = $this->config('bucket');
		
		if (empty($bucket)) {
			throw new \StorageException('You must set the bucket config');
		}
		
		$this->bucket = $bucket;
		
		$this->client = $amazon->get('s3');
	}
	
	/**
	 * Check if an item exists in storage driver.
	 *
	 * @param string $path The path to the item to check.
	 *
	 * @return bool
	 */
	public function exists($path)
	{
		return $this->client->doesObjectExist($this->bucket, $this->compute_path($path));
	}
	
	/**
	 * Loads item from storage driver.
	 * 
	 * @param string $path The path to the item to get.
	 *
	 * @return string
	 */
	public function load($path)
	{
		return (string) $this->client->getObject(array(
			'Bucket' => $this->bucket,
			'Key'    => $this->compute_path($path),
		))->get('Body');
	}
	
	/**
	 * Saves item in storage driver.
	 * 
	 * @param string $path The path to store item at.
	 * @param mixed  $data The data to store.
	 *
	 * @return bool
	 */
	public function save($path, $data)
	{
		return $this->client->putObject(array(
			'Bucket' => $this->bucket,
			'Key'    => $this->compute_path($path),
			'Body'   => $contents,
			'ACL'    => $this->config('acl', 'public-read'),
		));
	}
	
	/**
	 * Gets mime for an item in storage driver.
	 * 
	 * @param string $path The path to the item to get the mime.
	 *
	 * @return string
	 */
	public function mime($path)
	{
		$object = $this->client->headObject(array(
			'Bucket' => $this->bucket,
			'Key'    => $this->compute_path($path),
		));

		return $object['ContentType'];
	}
	
	/**
	 * Uploads an item in storage driver.
	 * 
	 * @param string $path The path to store item at.
	 * @param string $path The path to get item at.
	 *
	 * @return bool
	 */
	public function upload($path, $target)
	{
		$finfo = new \Finfo(FILEINFO_MIME_TYPE);
		
		return $this->client->putObject(array(
			'Bucket'      => $this->bucket,
			'Key'         => $this->compute_path($target),
			'SourceFile'  => $path,
			'ContentType' => $finfo->file($path),
			'ACL'         => $this->config('acl', 'public-read'),
		));
	}
	
	/**
	 * Downloads an item from storage driver.
	 * 
	 * @param string $path The path to get item at.
	 * @param string $path The path to store item at.
	 *
	 * @return bool
	 */
	public function download($path, $target)
	{
		return $this->client->getObject(array(
			'Bucket' => $this->bucket,
			'Key'    => $this->compute_path($path),
			'SaveAs' => $target,
		));
	}
	
	/**
	 * Delete an item in storage driver.
	 *
	 * @param string $path The path to item to delete.
	 *
	 * @return bool
	 */
	public function delete($path)
	{
		return $this->client->deleteObject(array(
			'Bucket' => $this->bucket,
			'Key'    => $this->compute_path($path),
		));
	}
	
	/**
	 * Gets the url to link to item in storage driver.
	 * 
	 * @param string $path The path to the item to link.
	 *
	 * @return string
	 */
	public function url($path)
	{
		return $this->client->getObjectUrl($this->bucket, $this->compute_path($path));
	}
	
	protected function ensure_bucket_exists()
	{
		if (!$this->bucket_exists()) {
			$this->client->createBucket(array(
				'Bucket' => $this->bucket,
			));

			$this->client->waitUntilBucketExists(array(
				'Bucket' => $this->bucket,
			));
		}
	}

	protected function bucket_exists()
	{
		return $this->client->doesBucketExist($this->bucket);
	}

	protected function compute_path($path)
	{
		$this->ensure_bucket_exists();

		return S3Client::encodeKey($path);
	}
}
