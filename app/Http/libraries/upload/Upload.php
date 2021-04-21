<?php
namespace Libraries\Upload;
use Application\Hash;
use Application\Helper;
/*
|--------------------------------------------------------------------------
| Upload Class 
|--------------------------------------------------------------------------
| This class handle uploading of multiple files
| 
|
*/
class Upload
{
	const KEY = 'fc01e8d00a90c1d392ec45459deb6f12'; // Please set your key for encryption here

	protected $_fileInput = array();

	protected $_files = array();
	
	protected $_fileNames = array();

	protected $_fileTypes = array();
	
	protected $_fileTempNames = array();
			
	protected $_fileExtensions = array();
			
	protected $_fileErrors = array();
	
	protected $_fileSizes = array();

	protected $_directoryPath = '/';

	protected $_debug = array();

	protected $_encryptedFileNames = array();

	protected $_allowedExtensions = array('jpg', 'png');

	protected $_maxSize = null;
	
	protected $_isMultiple = false;

	protected $_fileTypesToEncrypt = array();

	protected $_customErrorMessages = array();

	protected $_fileNameBase = '';


	/**
	 * Setting all the attributes with file data and check if it's single or multiple upload.
	 */
	public function __construct($input = null)
	{
		if(empty($input) && !isset($_FILES[$input]))
			return;

		$this->_fileNameBase = $input;

		$this->_fileInput = $_FILES[$input];
		$this->_isMultiple = $this->isMultiple($this->_fileInput);
		
		$this->_fileNames = $this->_fileInput['name'];
		$this->_fileTypes = $this->_fileInput['type'];
		$this->_fileTempNames = $this->_fileInput['tmp_name'];
		$this->_fileErrors = $this->_fileInput['error'];
		$this->_fileSizes = $this->_fileInput['size'];
		$this->_fileExtensions = $this->getFileExtensions();

		$this->_files = $this->orderFiles($this->_fileInput);
	}


	/**
	 * This method organized the files in a an array of keys for each file.
	 *
	 * @param Array | $files
	 * 
	 * @return Array | $sortedFiles
	 */
	public function orderFiles(Array $files)
	{
		$sortedFiles = array();
	
		foreach($files as $property => $values)
		{
			if(is_array($values))
			{
				foreach ($values as $key => $value)
				{
					$sortedFiles[$key] = array(
							'name' => $files['name'][$key],
							'encrypted_name' => '',
							'type' => $files['type'][$key],
							'extension' => $this->_fileExtensions[$key],
							'tmp_name' => $files['tmp_name'][$key],
							'error' => $files['error'][$key],
							'size' => $files['size'][$key],
							'encryption' => false,
							'success' => false,
							'errorMessage' => '',
					);
				}
			} else {
				$sortedFiles = array(
						'name' => $files['name'],
						'encrypted_name' => '',
						'type' => $files['type'],
						'extension' => $this->_fileExtensions[0],
						'tmp_name' => $files['tmp_name'],
						'error' => $files['error'],
						'size' => $files['size'],
						'encryption' => false,
						'success' => false,
						'errorMessage' => '',
				);
			}
		}

		return $sortedFiles;
	}


	/**
	 * This method allow the developer to set some rules for the upload process.
	 *
	 * @param Array | $rules
	 * 
	 * @return Object | $this
	 */
	public function addRules(Array $rules)
	{
		foreach($rules as $rule => $value)
		{
			switch($rule)
			{
				case 'size':
					$this->_maxSize = @intval($value);
					break;
				case 'extensions':
					if($extensions = explode('|', $value))
					{
						$this->_allowedExtensions = $extensions;
						break;
					}

					$this->_allowedExtensions[] = $value;
					break;
				default:
					$this->_debug[] = 'Sorry but this rule you specfied does not exist';
					break;
			}
		}

		return $this;
	}


	/**
	 * This method allows the developer to set custom error messages.
	 *
	 * @param Array | $errorMessages
	 * 
	 * @return Void
	 */
	public function customErrorMessages(Array $errorMessages)
	{
		foreach($errorMessages as $ruleName => $customMessage)
		{
			switch($ruleName)
			{
				case 'size':
					$this->_customErrorMessages[$ruleName] = $customMessage;
					break;
				case 'extensions':
					$this->_customErrorMessages[$ruleName] = $customMessage;
					break;
				default:
					$this->_debug[] = 'Sorry but this rule you specfied does not exist';
					break;
			}
		}
	}


	/**
	 * This method checks if its files or file.
	 *
	 * @param Array | $input
	 * 
	 * @return Boolean
	 */
	protected function isMultiple(Array $input)
	{
		if(isset($_FILES[$this->_fileNameBase]['name']) && !empty($_FILES[$this->_fileNameBase]['name']))
			return true;
		
		return false;
	}


	/**
	 * Get the extentions of the files.
	 *
	 * @return Array
	 */
	protected function getFileExtensions()
	{
		$extensions = array();

		if(is_array($this->_fileNames)) {
			foreach ($this->_fileNames as $filename) {
				$filename2 = explode('.', $filename);
				$str = end($filename2);
				$extension = strtolower($str);
				$extensions[] = $extension;
			}
		} else {
			$filename2 = explode('.', $this->_fileNames);
			$str = end($filename2);
			$extension = strtolower($str);
			$extensions[] = $extension;
		}

		return $extensions;
	}


	/**
	 * Set the path directory where you want to upload the files(if not specfied file/files 
	 * will be uploaded to the current directory).
	 *
	 * @param String
	 *
	 * @return Object | $this
	 */
	public function setDirectory($path)
	{
		if(substr($path , -1) == '/')
			$this->_directoryPath = $path;
		else
			$this->_directoryPath = $path . '/';

		return $this;
	}


	/**
	 * start the upload process
	 *
	 * @return Void
	 */
	public function start()
	{
		if(empty($this->_fileInput))
			return;


		if(!file_exists($this->_directoryPath))
		{
			$this->_debug[] = 'Sorry, but this path does not exists. you can also set create() to true.
									 Example: $this->setDirectory(\'images\')->create(true);';
			return;
		}

		if(self::isMultiDimensional($this->_files) == true)
		{
			foreach ($this->_files as $key => &$file)
			{
				if ($this->fileIsNotValid($file))
					continue;


				$fileToUpload = ($this->shouldBeEncrypted($file)) ? $this->_directoryPath . $file['encrypted_name'] : $this->_directoryPath . $file['name'];

				if (!move_uploaded_file($file['tmp_name'], $fileToUpload))
					$this->_files[$key]['success'] = false;
				else
					$this->_files[$key]['success'] = true;
			}
		} else {

			if ($this->fileIsNotValid($this->_files) === false) {

				$fileToUpload = ($this->shouldBeEncrypted($this->_files)) ? $this->_directoryPath . $this->_files['encrypted_name'] : $this->_directoryPath . $this->_files['name'];

				if (!move_uploaded_file($this->_files['tmp_name'], $fileToUpload)) {
					$this->_files['success'] = false;
				} else {
					$this->_files['success'] = true;
				}
			}

		}
	}


	/**
	 * This method checks if the file should be encrypted
	 *
	 * @param Array | $file
	 * 
	 * @return Boolean
	 */
	protected function shouldBeEncrypted($file)
	{
		return $file['encryption'];
	}


	/**
	 * This method decrypt the file name based on the key you specfied.
	 *
	 * @param $encryptedName
	 * 
	 * @return String | Decrypted File Name 
	 */
	public function decryptFileName($encryptedName)
	{
		$encryptedName = str_replace('#', '/' , $base64EncodedString);
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, static::KEY, base64_decode($encryptedCode), MCRYPT_MODE_ECB));
	}


	/**
	 * Save the file/files with the random name on the server(optional for security uses).
	 *
	 * @param Boolean | $generate
	 *
	 * @return Object | $this
	 */
	public function encryptFileNames($encrypt = false)
	{
		if($encrypt == false)
			return $this;

		if(empty(static::KEY))
		{
			$this->_debug[] = 'Please go to Upload.class.php file and set manually a key inside the const KEY
								     of 32 characters to encrypt your files. keep this key in safe place as well. 
								     you can call $this->generateMeAKey() to generate a random 32 characters key';
			return;
		}

		if(!empty($this->_fileInput))
		{
			if(is_array($this->_fileNames))
			{
				foreach ($this->_fileNames as $key => $fileName)
				{
					//$base64EncodedString = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, static::KEY, $fileName, MCRYPT_MODE_ECB));
					$base64EncodedString = base64_encode(Hash::getHash("md5", $fileName, HASH_KEY));
					$encryptedName = str_replace(array('/', '+', '='), array('#', '0', '0'), $base64EncodedString);

					$extension = $this->_fileExtensions[$key];
					$this->_files[$key]['encrypted_name'] = $encryptedName . "." . $extension;
				}
			} else {
				//$base64EncodedString = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, static::KEY, $this->_fileNames, MCRYPT_MODE_ECB));
				$base64EncodedString = base64_encode(Hash::getHash("md5", $this->_fileNames, HASH_KEY));
				$encryptedName = str_replace(array('/', '+', '='), array('#', '0', '0'), $base64EncodedString);

				$extension = $this->_fileExtensions[0];
				$this->_files['encrypted_name'] = $encryptedName . "." . $extension;
			}
		}

		return $this;
	}


	/**
	 * Allow the user to specify which file types to encrypt
	 *
	 * @param $types
	 *
	 * @return Void
	 */
	public function only($types)
	{
		if(is_array($types))
		{
			$this->_fileTypesToEncrypt = $types;
		}	
		else if(is_string($types))
		{
			if($extensions = explode('|', $types))
				$this->_fileTypesToEncrypt = $extensions;
		}

		if(self::isMultiDimensional($this->_files) == true)
		{
			foreach ($this->_files as $key => &$file)
			{
				if (in_array($this->_fileExtensions[$key], $this->_fileTypesToEncrypt))
					$file['encryption'] = true;
					$this->_files[$key]['encryption'] = true;
			}
		} else {
			$fileExtension = current($this->_fileExtensions);
			if (in_array($fileExtension, $this->_fileTypesToEncrypt))
				$file['encryption'] = true;
				$this->_files['encryption'] = true;
		}
		
		return;
	}


	/**
	 * This method create the directory if needed
	 * 
	 * @param Boolean | $create
	 *
	 * @return Void
	 */
	public function create($create = false)
	{
		if($create == false)
			return $this;

		if(!file_exists($this->_directoryPath))
			mkdir($this->_directoryPath);	
	}


	/**
	 * Check if extensions allowed
	 *
	 * @return Boolean
	 */
	protected function extensionsAllowed(&$file)
	{
		if(empty($this->_allowedExtensions) && empty($this->_fileExtensions))
			return;

		if(in_array($file['extension'], $this->_allowedExtensions)) {
			return true;
		}
	
		$file['success'] = false;
		$file['errorMessage'] = (isset($this->_customErrorMessages['extensions'])) ? $this->_customErrorMessages['extensions'] : "Sorry, but only " . implode( ", " , $this->_allowedExtensions ) . " files are allowed.";
		return false;
	}


	/**
	 * Check if the file size allowed
	 *
	 * @return Boolean
	 */
	protected function maxSizeOk(&$file)
	{
		if(empty($this->_maxSize) && empty($this->_fileSizes))
			return;
			
		if($file['size'] < ($this->_maxSize * 1000))
			return true;
		
		$file['errorMessage'] = (isset($this->_customErrorMessages['size'])) ? $this->_customErrorMessages['size'] : "Sorry, but your file, " . $file['name'] . ", is too big. maximal size allowed " . $this->_maxSize . " Kbyte";
		
		return false;	
	}


	/**
	 * Check if file validation fails
	 *
	 * @return Boolean
	 */
	protected function fileIsNotValid(&$file)
	{
		if($file['error'] !== UPLOAD_ERR_OK) 
	    {
	    	$this->_debug[] = 'El archivo ' . $file['name'] . ' no se pudo cargar. Asegúrese de que su archivo php.ini permita cargar el tamaño del archivo.';
	    	$file['errorMessage'] = 'Archivo inválido: ' . $file['name'];
	    	return false;
	    }

		if($this->extensionsAllowed($file) && $this->maxSizeOk($file)) {
			return false;
		}
	
		return true;
	}


	/**
	 * This method checks if the upload was unsuccessful.
	 * 
	 * @return Boolean
	 */
	public function unsuccessfulFilesHas()
	{
		if(self::isMultiDimensional($this->_files) == true) {
			foreach ($this->_files as $file) {
				if ($file['success'] == false && !empty($file['errorMessage']))
					return true;
			}
		} else {
			if ($this->_files['success'] == false && !empty($this->_files['errorMessage']))
				return true;
		}
		
		return false;
	}


	/**
	 * This method checks if the upload was successful.
	 * 
	 * @return Boolean
	 */
	public function successfulFilesHas()
	{
		if(self::isMultiDimensional($this->_files) == true) {
			foreach($this->_files as $file) {
				if($file['success'] == true)
					return true;
			}
		} else {
			if($this->_files['success'] == true)
				return true;
		}
		
		return false;
	}

	/**
	 * This method get the errors array to give some feedback to the user.
	 *
	 * @return Array  
	 */
	public function errorFiles()
	{
		$failedUploads = array();

		if(self::isMultiDimensional($this->_files) == true)
		{
			foreach ($this->_files as $key => $file)
			{
				if ($file['success'] == true)
					continue;

				$failedFile = new \stdClass();

				$failedFile->name = $file['name'];

				if ($this->shouldBeEncrypted($file))
					$failedFile->encryptedName = $file['encrypted_name'];

				$failedFile->type = $file['type'];
				$failedFile->extension = $file['extension'];
				$failedFile->size = $file['size'];
				$failedFile->error = $file['error'];

				if (!empty($file['errorMessage']))
					$failedFile->errorMessage = $file['errorMessage'];

				$failedUploads[] = $failedFile;
			}
		} else {

			if ($this->_files['success'] == false) {

				$failedFile = new \stdClass();

				$failedFile->name = $this->_files['name'];

				if ($this->shouldBeEncrypted($this->_files))
					$failedFile->encryptedName = $this->_files['encrypted_name'];

				$failedFile->type = $this->_files['type'];
				$failedFile->extension = $this->_files['extension'];
				$failedFile->size = $this->_files['size'];
				$failedFile->error = $this->_files['error'];

				if (!empty($this->_files['errorMessage']))
					$failedFile->errorMessage = $this->_files['errorMessage'];

				$failedUploads[] = $failedFile;
			}
		}
						
		return $failedUploads;
	}


	/**
	 * This method get the errors array to give some feedback to the user.
	 *
	 * @return Array  
	 */
	public function successFiles()
	{
		$successfulUploads = array();

		if(self::isMultiDimensional($this->_files) == true)
		{
			foreach ($this->_files as $key => $file)
			{
				if ($file['success'] == false)
					continue;

				$successfulFile = new \stdClass();

				$successfulFile->name = $file['name'];

				if ($this->shouldBeEncrypted($file))
					$successfulFile->encryptedName = $file['encrypted_name'];

				$successfulFile->type = $file['type'];
				$successfulFile->extension = $file['extension'];
				$successfulFile->size = $file['size'];

				$successfulUploads[] = $successfulFile;
			}
		} else {

			if ($this->_files['success'] == true) {

				$successfulFile = new \stdClass();

				$successfulFile->name = $this->_files['name'];

				if ($this->shouldBeEncrypted($this->_files))
					$successfulFile->encryptedName = $this->_files['encrypted_name'];

				$successfulFile->type = $this->_files['type'];
				$successfulFile->extension = $this->_files['extension'];
				$successfulFile->size = $this->_files['size'];

				$successfulUploads[] = $successfulFile;
			}
		}
						
		return $successfulUploads;
	}


	/**
	 * This method displays the errors formated nicely with bootstraps.
	 * 
	 * @return Void
	 */
	public function displayErrors()
	{
		foreach($this->errorFiles() as $file)
	    {
	      echo '<div class="alert alert-danger">No se ha logrado subir el archivo ' . $file->name .'. '. $file->errorMessage . '</div>';
	    }
	}


	/**
	 * This method displays the errors formated nicely with bootstraps.
	 * 
	 * @return Void
	 */
	public function displaySuccess()
	{
		foreach($this->successFiles() as $file)
	    {
	      	echo '<div class="alert alert-success">' . $file->name .' cargado correctamente</div>';
	    }
	}


	/**
	 * This method check if the file is set. normally when the user submits the form.
	 * 
	 * @return Boolean
	 */
	public static function formIsSubmitted()
	{
		if(empty($_FILES))
			return false;

		/*if($_FILES['file']['size'] <= 0)
			return false;*/
		
		return true;
	}


	/**
	 * A simple gererator of a random key to use for encrypting 
	 */
	public static function generateMeAKey()
	{
		echo md5(uniqid());
	}


	/**
	 * This method get the errors array to give some feedback to the developer.
	 *
	 * @return Array  
	 */
	public function debug()
	{
		return $this->_debug;
	}

	public static function isMultiDimensional($a) {
		$c = count($a);
		for ($i=0;$i<$c;$i++) {
			if(isset($a[$i])) {
				if (is_array($a[$i])) return true;
			}
		}
		return false;
	}
}