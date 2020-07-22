<?php
namespace Upload;
use \Models\Files as Files;

class File extends Files
{
    /**sample input array--> 
     * $file = array(
     *   'file'=> $file,
     *   'destination'=> $destination,
     *   'check'=> array('extensions'=>array('jpeg', 'png', 'jpg'), 'max_size'=>'204801'),
     *   'query'=> array('insert'=>array('table'=>'payment', 'column'=>'column_name'), 
     *                  'update'=>array('table'=>'table_name', 'column'=>'column_name', 
     *                  'match'=>'matching_row', 'match_data'=>'match_data')
     *                  )
     *    )
     */
    public static function upload($file)
    {
        ///making sure that all cumpolsory keys are present in the input
        if (!array_key_exists("file",$file))
        {
            return 'please provide the file to be uploaded';
        }

        if (!array_key_exists("destination",$file))
        {
            return 'please provide the destination to be uploaded to';
        }
        // end of check of cumpolsory keys

        //optional variables
        if (array_key_exists("check",$file))
        {
            $check = $file['check'];
        }

        if (array_key_exists("query", $file))
        {
            $query = $file['query'];
        }

        /**parameters definitions */
        $file = $file['file'];
        $destination = $file['destination'];

        $file_name = time().$file['name'];
        $file_size =$file['size'];
        $file_tmp =$file['tmp_name'];
        $file_type=$file['type'];
        $fileto = $destination.$file_name;                  
        $file_ext=strtolower(end(explode('.',$fileto)));

        //check for extentions and file size
        if (!empty($check)) 
        {

            //now we check if their is an extension array present
            if (array_key_exists("extensions",$check))
            {
                //map the extentions array to a variable
                $extensions = $check['extensions'];

                //check to see if its an acceptable extension
                if(in_array($file_ext,$extensions)=== false)
                {
                    $mssg = "<div class='alert alert-danger'>
                    <strong>Error!</strong> Extension not allowed Please choose a explode(',', $extensions) files(s)
                    </div>";
                    return $mssg;
                }
            }
            ////////////////////////////////////////////////////////

            //now we check if their is a max_size value present
            if (array_key_exists("max_size",$check))
            {
                //map the max_size to a variable
                $max_size = $check['max_size'];

                //check to see if its an acceptable max size
                if($file_size > $max_size)
                {
                    $mssg = "<div class='alert alert-danger'>
                    <strong>Error!</strong> File size shouldn't exceed 200kb
                    </div>";
                    return $mssg;
                }
            }
            /////////////////////////////////////////////////////////
        }
        
        //try moving file to destination
        if(move_uploaded_file($file_tmp,$fileto))
        {
        
            //check if a database insert is needed for the file
            if (!empty($query)) 
            {

                if (array_key_exists("update",$query))
                {
                    $queryType = 'update';
                    $query = $query['update'];

                    //make sure the needed data is available
                    if (!array_key_exists("table",$query) OR !array_key_exists("column",$query) OR !array_key_exists("match",$query) !array_key_exists("match_data",$query))
                    {
                        return 'please provide all update query parameters';
                    }

                }
                elseif (array_key_exists("insert",$query))
                {
                    $queryType = 'insert';
                    $query = $query['insert'];

                    //make sure the needed data is available
                    if (!array_key_exists("table", $query) OR !array_key_exists("column",$query)) {
                        return 'please provide all insert query parameters';
                    }
                }
                else
                {
                    return 'invalid query type';
                }

                //switch between query types
                switch ($queryType) {
                    case 'update':
                        $run=(new self)->saveUpdate($query['table'], $query['column'], $fileto, $query['match'], $query['match_data']);
                        break;
                    
                    case 'insert':
                        $run = (new self)->saveInsert($query['table'], $query['column'], $fileto);
                        break;
                }

            }
            
        }

            if ($run!=TRUE) {
                $status = 'error';
                $mssg = "<div class='alert alert-danger'>
                            <strong>Error!</strong> an error occured in the query run sector, please try again.
                        </div>";
        
                return  array('status'=>$status, 'message'=>$mssg);
            }

                $status = 'success';
                $mssg = "<div class='alert alert-success'>
                                <strong>Nice!</strong> Your payment evidence was submitted successfully
                            </div>";
            
                return  array('status'=>$status, 'message'=>$mssg);
    }
}