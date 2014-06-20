<?php
class Op3nVoice {
  function Op3nVoice() {
    require __DIR__.'/vendor/autoload.php';
    $this->audio = new \OP3Nvoice\Bundle($apikey);
    $this->message = '';
  }

  // Array with video path and name
  // $audio = array(
  //     'media_url' => 'https://s3-us-west-2.amazonaws.com/op3nvoice/harvard-sentences-1.wav',
  //     'name' => 'name' . rand(0, 500),
  // );
  function Op3nVoice_resource_create($audio) {
    $success = $this->audio->create($audio);

    if ($success) {
      $newURI = $this->audio->detail['_links']['self']['href'];
      //echo $newURI . "\n";
      //print_r($item);
      $this->message = $newURI;
      $this->item = $this->audio->load($newURI);
      // Update drupal db with changes
    } else {
      //echo $audio->detail['message'] . "\n";
      $this->message = $this->audio->detail['message'];
    }
  }

  function Op3nVoice_resource_list() {
    
    $items = $this->audio->index();
    
    foreach ($items as $item) {
        $bundle = $audio->load($item['href']);

        echo $bundle['_links']['self']['href'] . "\n";
        echo $bundle['name'] . "\n";
    }
  }

  function Op3nVoice_resource_delete($items) {

    $item = $this->audio->delete($item['href']);
    $bundle = $this->audio->load($item['href']);
    // Update drupal db with changes

    print_r($bundle);
  }

  function Op3nVoice_resource_metadata_delete($items) {
    foreach ($items as $item) {
        $metadata = $this->audio->metadata;
        $data = $metadata->load($item['href']);
        print_r($data);

        $metadata->update(
            array(
                'id' => $item['href'],
                'data' => '{"status": "This is awesome!!"}',
            )
        );
        $data = $metadata->load($item['href']);
        print_r($data);

        $metadata->delete($item['href']);
        $data = $metadata->load($item['href']);
        // Update drupal db with changes
        print_r($data);
    }
  }

  function Op3nVoice_resource_metadata_load($item){
    foreach ($items as $item) {
        $metadata = $this->audio->metadata->load($item['href']);

        print_r($metadata);
    }
  }

  function Op3nVoice_resource_metadata_update($item){
    foreach ($items as $item) {
        $metadata = $this->audio->metadata;
        $data = $metadata->load($item['href']);
        print_r($data);

        $metadata->update(
            array(
                'id' => $item['href'],
                'data' => '{"status": "This is awesome!!"}',
            )
        );
        $data = $metadata->load($item['href']);
        print_r($data);
        // Update drupal db with changes
    }
  }

  function Op3nVoice_search($term){
    $result = $this->audio->search($term);

    $results = $result['item_results'];
    $items = $result['_links']['items'];
    foreach ($items as $index => $item) {
        $bundle = $this->audio->load($item['href']);

        echo $bundle['_links']['self']['href'] . "\n";
        echo $bundle['name'] . "\n";

        $search_hits = $results[$index]['term_results'][0]['matches'][0]['hits'];
        foreach ($search_hits as $search_hit) {
            echo $search_hit['start'] . ' -- ' . $search_hit['end'] . "\n";
        }
    }
  }

  function Op3nVoice_tracks_create($items){
    foreach ($items as $item) {
        $tracks = $audio->tracks->load($item['href']);
        print_r($tracks);

        $success = $audio->tracks->create(
            array(
                'id' => $item['href'],
                'media_url' => 'https://s3-us-west-2.amazonaws.com/op3nvoice/harvard-sentences-1.wav',
            )
        );
        $success = $audio->tracks->create(
            array(
                'id' => $item['href'],
                'media_url' => 'https://s3-us-west-2.amazonaws.com/op3nvoice/harvard-sentences-2.wav',
            )
        );
        $success = $audio->tracks->create(
            array(
                'id' => $item['href'],
                'media_url' => 'https://s3-us-west-2.amazonaws.com/op3nvoice/dorothyandthewizardinoz_01_baum_64kb.mp3',
            )
        );

        $tracks = $audio->tracks->load($item['href']);
        print_r($tracks);
        die();
    }
  }

  function Op3nVoice_tracks_create($items){
    foreach ($items as $item) {
        $tracks = $audio->tracks->load($item['href']);
        print_r($tracks);

        $success = $audio->tracks->create(
            array(
                'id' => $item['href'],
                'media_url' => 'https://s3-us-west-2.amazonaws.com/op3nvoice/harvard-sentences-1.wav',
            )
        );
        $success = $audio->tracks->create(
            array(
                'id' => $item['href'],
                'media_url' => 'https://s3-us-west-2.amazonaws.com/op3nvoice/harvard-sentences-2.wav',
            )
        );
        $success = $audio->tracks->create(
            array(
                'id' => $item['href'],
                'media_url' => 'https://s3-us-west-2.amazonaws.com/op3nvoice/dorothyandthewizardinoz_01_baum_64kb.mp3',
            )
        );

        $tracks = $audio->tracks->load($item['href']);
        print_r($tracks);
        die();
    }
  }


}





function op3nvoice_load_resource() {
  $result = $audio->create('http://example.com/sample-audio-file.wav', 'optional bundle name');
}

function op3nvoice_search_resource($terms) {
  $terms = $terms ? $terms : 'no search specified';
  $terms = preg_replace("/[^A-Za-z0-9|]/", "", $terms);

  $audio = new \OP3Nvoice\Bundle($apikey);
  $items = $audio->search($terms);
}

function op3nvoice_process_result($result) {
  $results = $result['item_results'];
  $items = $result['_links']['items'];
  foreach($items as $index => $item) {
      $bundle = $audio->load($item['href']);

      echo $bundle['_links']['self']['href'] . "\n";
      echo $bundle['name'] . "\n";

      $search_hits = $results[$index]['term_results'][0]['matches'][0]['hits'];
      foreach($search_hits as $search_hit) {
          echo $search_hit['start'] . ' -- ' . $search_hit['end'] . "\n";
      }
  }
}

function op3nvoice_search_page($results) {
  $output['prefix']['#markup'] = '<ol class="search-results">';

  foreach ($results as $entry) {
    $output[] = array(
      '#theme' => 'search_result',
      '#result' => $entry,
      '#module' => 'my_module_name',
    );
  }
  $output['suffix']['#markup'] = '</ol>' . theme('pager');

  return $output;
}

function op3nvoice_search_info() {
  return array(
    'title' => 'Content',
    'path' => 'node',
    'conditions_callback' => 'callback_search_conditions',
  );
}

function op3nvoice_search_admin() {
  // Output form for defining rank factor weights.
  $form['content_ranking'] = array(
    '#type' => 'fieldset',
    '#title' => t('Content ranking'),
  );
  $form['content_ranking']['#theme'] = 'node_search_admin';
  $form['content_ranking']['info'] = array(
    '#value' => '<em>' . t('The following numbers control which properties the content search should favor when ordering the results. Higher numbers mean more influence, zero means the property is ignored. Changing these numbers does not require the search index to be rebuilt. Changes take effect immediately.') . '</em>',
  );

  // Note: reversed to reflect that higher number = higher ranking.
  $options = drupal_map_assoc(range(0, 10));
  foreach (module_invoke_all('ranking') as $var => $values) {
    $form['content_ranking']['factors']['node_rank_' . $var] = array(
      '#title' => $values['title'],
      '#type' => 'select',
      '#options' => $options,
      '#default_value' => variable_get('node_rank_' . $var, 0),
    );
  }
  return $form;
}

function op3nvoice_search_reset() {
  db_update('search_dataset')->fields(array('reindex' => REQUEST_TIME))->condition('type', 'node')->execute();
}

function op3nvoice_search_access() {
  return user_access('access content');
}

function op3nvoice_search_execute($keys = NULL, $conditions = NULL) {
  // Build matching conditions
  $query = db_select('search_index', 'i', array('target' => 'slave'))->extend('SearchQuery')->extend('PagerDefault');
  $query->join('node', 'n', 'n.nid = i.sid');
  $query->condition('n.status', 1)->addTag('node_access')->searchExpression($keys, 'node');

  // Insert special keywords.
  $query->setOption('type', 'n.type');
  $query->setOption('language', 'n.language');
  if ($query->setOption('term', 'ti.tid')) {
    $query->join('taxonomy_index', 'ti', 'n.nid = ti.nid');
  }
  // Only continue if the first pass query matches.
  if (!$query->executeFirstPass()) {
    return array();
  }

  // Add the ranking expressions.
  _node_rankings($query);

  // Load results.
  $find = $query->limit(10)->execute();
  $results = array();
  foreach ($find as $item) {
    // Build the node body.
    $node = node_load($item->sid);
    node_build_content($node, 'search_result');
    $node->body = drupal_render($node->content);

    // Fetch comments for snippet.
    $node->rendered .= ' ' . module_invoke('comment', 'node_update_index', $node);
    // Fetch terms for snippet.
    $node->rendered .= ' ' . module_invoke('taxonomy', 'node_update_index', $node);

    $extra = module_invoke_all('node_search_result', $node);

    $results[] = array(
      'link' => url('node/' . $item->sid, array('absolute' => TRUE)),
      'type' => check_plain(node_type_get_name($node)),
      'title' => $node->title,
      'user' => theme('username', array('account' => $node)),
      'date' => $node->changed,
      'node' => $node,
      'extra' => $extra,
      'score' => $item->calculated_score,
      'snippet' => search_excerpt($keys, $node->body),
    );
  }
  return $results;
}

function op3nvoice_search_execute($keys = NULL, $conditions = NULL) {
  // Build matching conditions
  $query = db_select('search_index', 'i', array('target' => 'slave'))->extend('SearchQuery')->extend('PagerDefault');
  $query->join('node', 'n', 'n.nid = i.sid');
  $query->condition('n.status', 1)->addTag('node_access')->searchExpression($keys, 'node');

  // Insert special keywords.
  $query->setOption('type', 'n.type');
  $query->setOption('language', 'n.language');
  if ($query->setOption('term', 'ti.tid')) {
    $query->join('taxonomy_index', 'ti', 'n.nid = ti.nid');
  }
  // Only continue if the first pass query matches.
  if (!$query->executeFirstPass()) {
    return array();
  }

  // Add the ranking expressions.
  _node_rankings($query);

  // Load results.
  $find = $query->limit(10)->execute();
  $results = array();
  foreach ($find as $item) {
    // Build the node body.
    $node = node_load($item->sid);
    node_build_content($node, 'search_result');
    $node->body = drupal_render($node->content);

    // Fetch comments for snippet.
    $node->rendered .= ' ' . module_invoke('comment', 'node_update_index', $node);
    // Fetch terms for snippet.
    $node->rendered .= ' ' . module_invoke('taxonomy', 'node_update_index', $node);

    $extra = module_invoke_all('node_search_result', $node);

    $results[] = array(
      'link' => url('node/' . $item->sid, array('absolute' => TRUE)),
      'type' => check_plain(node_type_get_name($node)),
      'title' => $node->title,
      'user' => theme('username', array('account' => $node)),
      'date' => $node->changed,
      'node' => $node,
      'extra' => $extra,
      'score' => $item->calculated_score,
      'snippet' => search_excerpt($keys, $node->body),
    );
  }
  return $results;
}