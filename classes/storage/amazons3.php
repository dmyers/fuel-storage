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
	 * Loads item from storage driver.
	 * 
	 * @param string $path The path to the item to get.
	 *
	 * @return bool|string
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
			$client->createBucket(array(
				'Bucket' => $bucket,
			));

			$client->waitUntilBucketExists(array(
				'Bucket' => $bucket,
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
