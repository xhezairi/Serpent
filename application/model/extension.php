<?

class extension_model {
	public function add()
	{
		$extension_name 	= input::post ( 'extension_name' );
		$extension_type 	= input::post ( 'extension_type' );
		
		$repo_name		 	= input::post ( 'repo_name' );
		
		$description 		= input::post ( 'description' );
		$version 			= input::post ( 'version' );
		$requires			= '';
		$tested				= '';
		$author_id			= user::id( );
		$website			= input::post ( 'website' );
			
		$inflector = new inflector( );
		$extension_slug = $inflector->camelize( $extension_name );
		$extension_slug = $inflector->underscore( $extension_name );
		
		$extension  = db( 'extension' );

		$extension_id = $extension->insert(array(
			'extension_name'	=>$extension_name,
			'extension_slug'	=>$extension_slug,
			'extension_type'	=>$extension_type,
			'repo_name'			=>$repo_name,
			'revision'			=> 0,
			'added_on'			=>time(),
			'description'		=>$description,
			'version'			=>$version,
			'author_id'			=>$author_id,
			'website'			=>$website
		));

		note::set('success','extension_add','Extension Added!');
		
		return $extension_id ;
	}
	
	
	public function version()
	{
		$extension_name 	= input::post ( 'extension_name' );
		$extension_revision = input::post ( 'exension_revision' );
		$extension_slug 	= input::post ( 'extension_slug' );
		$extension_type 	= input::post ( 'extension_type' );
		$extension_version	= input::post ( 'extension_version' );
		$added_on			= input::post ( 'added_on' );
		$repo_name			= input::post ( 'repo_name' );
		
		$description 		= input::post ( 'description' );
		$version 			= input::post ( 'version' );
		$requires			= '';
		$tested				= '';
		$author_id			= user::id( );
		$website			= input::post ( 'website' );
		
		$extension  = db( 'extension' );

		$extension_id = $extension->insert(array(
			'extension_name'	=>$extension_name,
			'extension_slug'	=>$extension_slug,
			'extension_type'	=>$extension_type,
			'repo_name'			=>$repo_name,
			'revision'			=>$extension_revision+1,
			'added_on'			=>$added_on,
			'modified_on'		=>time(),
			'description'		=>$description,
			'version'			=>$version,
			'author_id'			=>$author_id,
			'website'			=>$website
		));

		note::set('success','extension_add','Extension Added!');
		
		return $extension_id ;
	}


	public function version_old ( $old_id = '', $type = '' )
	{
		// update old
		$users_table = db ( 'extension' );
			
		if ( $type == 'plugin' ):
			$type = 'plugin_revision';
		else:
			$type = 'theme_revision';
		endif;
		
			
		$users_table->update(array(
				'extension_type'=> $type
			))
			->where( 'id', '=', $old_id )
			->execute();
			
			die;
			
			return TRUE;
	}
	
	
	public function get( $slug = '', $active = '1' )
	{
		$extension_table = db ( 'extension' );

		if ( is_numeric( $slug ) ) {
			$get = $extension_table->select( '*' )
				->where ( 'id', '=', $slug)
				->clause('AND')
				->where ( 'extension_type', '!=', 'theme_revision')
				->clause('AND')
				->where ( 'extension_type', '!=', 'plugin_revision')
				->clause('AND')
				->where ( 'active', '=', $active)
				->execute();
		} else {	
			$get = $extension_table->select( '*' )
				->where ( 'extension_slug', '=', $slug)
				->clause('AND')
				->where ( 'extension_type', '!=', 'theme_revision')
				->clause('AND')
				->where ( 'extension_type', '!=', 'plugin_revision')
				->clause('AND')
				->where ( 'active', '=', $active)
				->execute();
		}

		if ( $get ):
			return $get[0];
		else:
			return FALSE;
		endif;
	}
	
	
	public function get_versions( $slug = '' )
	{
		$extension_table = db ( 'extension' );

		$get = $extension_table->select( '*' )
			->where ( 'extension_slug', '=', $slug)
			->order_by( 'id', 'DESC' )
			->execute();

		// revisions are returned newest to oldest
		return $get;
	}


	public function get_themes( $slug = '', $active = '1' )
	{
		$extension_table = db ( 'extension' );

		$get_themes = $extension_table->select( '*' )
			->where ( 'extension_type', '=', 'theme')
			->clause('AND')
			->where ( 'active', '=', $active)
			->execute();
			
		return $get_themes;
	}


	public function get_plugins( $slug = '', $active = '1' )
	{
		$extension_table = db ( 'extension' );
		
		$get_plugins = $extension_table->select( '*' )
			->where ( 'extension_type', '=', 'plugin')
			->clause('AND')
			->where ( 'active', '=', $active)
			->execute();
			
		return $get_plugins;
	}

	
	public function get_core( $slug = '', $active = '1' )
	{
		$extension_table = db ( 'extension' );

		$get_themes = $extension_table->select( '*' )
			->where ( 'extension_type', '=', 'core')
			->clause('AND')
			->where ( 'active', '=', $active)
			->execute();
			
		return $get_themes;
	}
}