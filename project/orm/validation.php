<?php
	class Validation
	{
		public $pwd=0;
		public $name=" ";
		public $errors=array();
		public function validate($data,$rules,$image='null',$upload='null')
        	{
        		//var_dump($data);
        		//var_dump($rules);
        		//var_dump($upload);
			$valid=TRUE;
			foreach($rules as $fieldname=>$rule)
			{
				$callbacks=explode('%',$rule);
				foreach($callbacks as $callback)
				{
					$value=isset($data[$fieldname]) ? $data[$fieldname] : NULL;
					if ($this->$callback($value,$fieldname)==FALSE)
					{
						$valid=FALSE;
					}
				}
			}
			$this->image($image,$upload);
			return $valid;
		}

		public function email($value,$fieldname) 
		{ 
			//var_dump($fieldname);
			if(preg_match("/^[a-z]([a-z0-9]+|[a-z0-9]+[a-z0-9.]+)*@[a-z0-9]+\.[a-z]+/",$value))
			{
				$valid=TRUE; 
			}
			else
			{
				$valid=FALSE; 
			}
			if($valid==FALSE)
			{
				$this->errors[]="$fieldname is invalid".'<br>';
			}
			return $valid;
		}

		public function required($value,$fieldname)
        	{
			$valid=!empty($value);
			if($fieldname)
			{
				$this->name=$value;
			}
			if($valid==FALSE)
			{
				$this->errors[]="$fieldname is required".'<br>';
			}
		return $valid;
        	}
        	
		public function length($value,$fieldname)
        	{
        		$this->pwd=$value;
        		if(count(str_split($this->pwd))>=8)
        		{
        			$valid=TRUE;
        		}
        		else
        		{
        			$valid=FALSE;
        			$this->errors[]="$fieldname is short".'<br>';
        		}
		 return $valid;
        	}
        	
        	public function samePwd($value,$fieldname)
        	{
        		$confirmPwd=$value;
        		if($confirmPwd==$this->pwd)
        		{
        			$valid=TRUE;
        		}
        		else
        		{
        			$valid=FALSE;
        			$this->errors[]="$fieldname is wrong ".'<br>';
        		}
		return $valid;
              }
        	
        	public function image($image,$upload)
        	{
			if($image!='null'){
        		if ($image['error'] > 0)
			{
				$valid=FALSE;
				switch ($image['error'])
				{
					case 1: $this->errors[]="image is exceeded upload_max_filesize";
						break;
					case 2: $this->errors[]="image is exceeded max_file_size";
						break;
					case 3: $this->errors[]="image is only partially uploaded";
						break;
					case 4:  $this->errors[]= "image isnot uploaded";
						break;
					case 6: $this->errors[]="image cannot be uploaded : No temp directory specified";
						break;
					case 7: $this->errors[]="image uploading is failed: Cannot write to disk";
						break;
			}

	} else {
	$types=array('image/gif','image/jpeg','image/png','image/psd','image/bmp','image/tiff');
	// put the file where we’d like it
	$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];

	$upfile = "$DOCUMENT_ROOT/project/uploads/".$upload."/".$image['name'];
	//var_dump($upfile);
	// Does the file have the right MIME type?
	if (in_array($image['type'],$types))
	{
		if (is_uploaded_file( $image['tmp_name']))
		{
			if (!move_uploaded_file($image['tmp_name'], $upfile))
			{
				//var_dump(!move_uploaded_file($image['tmp_name'], $upfile));

				$valid=FALSE;
				$this->errors[]="image Could not be moved to destination directory";
			}
			else
			{
				$valid=TRUE;
			}
		}
		
	} else {
		$valid=FALSE;
		$this->errors[]="Invalid type";
	  } 

	}
        	}
	}
	}
?>
