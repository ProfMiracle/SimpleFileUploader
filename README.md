# SimpleFileUploader
Simplifies PHP upload functions<br>
# sample input array <br><br>
      $file = array(
        'file'=> $file,
        'destination'=> $destination,
        'check'=> array('extensions'=>array('jpeg', 'png', 'jpg'), 'max_size'=>'204801'),
        'query'=> array('insert'=>array('table'=>'payment', 'column'=>'column_name'), 
                       'update'=>array('table'=>'table_name', 'column'=>'column_name', 
                       'match'=>'matching_row', 'match_data'=>'match_data')
                       )
         )
# sample call
File::upload($file)
