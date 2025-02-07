<?php
	class Openai_Seo_Command{
		const OPENAI_COMPLETION_URL = 'https://api.openai.com/v1/completions';
		const OPENAI_API_KEY = 'sk-xxxxxxxxxxxxxx';
		
		/**
		 * Use content to create tags list and an SEO excerpt
		 *
		 *
		 * ## OPTIONS
		 *
   		 *	<post_type>
		 *	<number_of_posts>
		 * 	<offset>
		 *
		 * ## EXAMPLES
		 *
		 *     $ wp openai-seo post 20 0
		 *
		 *
		 *	@when plugins_loaded
		 *
		 *	
		 */
		function __invoke($args){
			list($post_type, $number_of_posts, $offset) = $args;
			
			$posts = get_posts(
				array(
					'post_type'		=>		$post_type,
					'post_status'		=>		'publish',
					'posts_per_page'	=>		'all' !== $number_of_posts ? $number_of_posts : -1,
					'offset'		=>		isset($offset) ? $offset : 0
				)
			);
			
			if(!empty($posts)){
				foreach($posts as $post){
					if(
						!empty($post->post_content) 
					){
						$generated = $this->terms_create($post);
					
						echo "TERMS: " .  $generated["result"];

						if($generated["error"] == 0){
							$terms = wp_set_post_terms(
								$post->ID, 
								$generated["result"],
								'post_tag',
								TRUE //append
							);

							if(FALSE !== $terms && !is_wp_error($terms) && !empty($terms) && empty($post->post_excerpt)){
								WP_CLI::success("POST TERMS UPDATED ID: ${$post->ID}");

								$excerpt = $this->excerpt_create($post, $terms);
								echo "EXCERPT: " . $excerpt["result"];
								if($excerpt["error"] == 0){
									$update = wp_update_post(
										array(
											'ID'			=>		$post->ID,
											'post_excerpt'	=>		$excerpt["result"]
										),
										TRUE
									);

									if(!is_wp_error($update)){
										WP_CLI::success("POST EXCERPT UPDATED ID: $update");
									}else{
										WP_CLI::error("POST EXCERPT UPDATE ERROR ID: $update", FALSE); //exit false 
									}
								}
							}else{
								WP_CLI::error("POST TERMS UPDATE ERROR ID: ${$post->ID}", FALSE);
							}
						}
					}else{
						echo "WTF!!!!";
					}
				}
			}
		}
		
		private function terms_create($post){
			$args = array(
				'model'  => "gpt-3.5-turbo",
				'messages' => array(
					array(
						"role"			=>		"user",
						"content"		=>		sprintf(
							"Given the following post, create a comma-delimited, double-quoted list of one to three-word SEO keywords, choosing only the 10 most important keywords:\nTitle: %s\nContent: %s",
							htmlspecialchars_decode($post->post_title),
							strip_tags(htmlspecialchars_decode($post->post_content))
						)
					)
				),
				'max_tokens' => 256,
				'temperature'	=> 0.77,
				'frequency_penalty'	=> 0,
				'presence_penalty'	=> 0,
				'top_p'	=>	1,
				'stop' => array(";")
			);

		
			$key = self::OPENAI_API_KEY;
			$ch = curl_init(self::OPENAI_COMPLETION_URL);

			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, TRUE);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Authorization: Bearer $key",
				'Content-Type: application/json'
			));

			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args));

			$result = curl_exec($ch);

			if(!empty(curl_error($ch)))
				return array("result" => "", "error" =>  curl_error($ch));
			
			curl_close($ch);

			$response = json_decode($result, TRUE);

			return array("result" => str_replace('"', "", stripslashes($response['choices'][0]['message']['content'])), "error" => 0); //remove slashes and double quotes
		}
		
		private function excerpt_create($post, $terms){
			$args = array(
				'model'  => "gpt-3.5-turbo",
				'messages' => array(
					array(
						'role'		=>		"user",
						"content"	=>		sprintf(
							"Given the following post and terms, create an SEO excerpt that is no longer than 55 words:\nTitle: %s\nContent: %s\nTerms: %s",
							htmlspecialchars_decode($post->post_title),
							substr(strip_tags(htmlspecialchars_decode($post->post_content)), 0, 10000), //limit to 10,000 characters - up to 2500 words
							$terms
						)
					)
				),
				'max_tokens' => 256,
				'temperature'	=> 0.77,
				'frequency_penalty'	=> 0,
				'presence_penalty'	=> 0,
				'top_p'	=>	1,
				'stop' => array(";")
			);

			$key = self::OPENAI_API_KEY;
			$ch = curl_init(self::OPENAI_COMPLETION_URL);

			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, TRUE);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Authorization: Bearer $key",
				'Content-Type: application/json'
			));

			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args));

			$result = curl_exec($ch);

			

			if(!empty(curl_error($ch)))
				return array("result" => "", "error" =>  curl_error($ch));

			
			curl_close($ch);
			
			$response = json_decode($result, TRUE);
			
			return array("result" => $response['choices'][0]['message']['content'], "error" => 0); 
		}
	}

	WP_CLI::add_command( 'openai-seo', 'Openai_Seo_Command');
?>
