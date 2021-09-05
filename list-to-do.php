<?php include "inc/table_create.php";?>
<?php
/**
* Plugin Name: List to do
* Plugin URI: 
* Description: Plugin with to do list
* Version: 1.0
* Author: Mariusz Suski
**/
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
 
<script
  src="https://code.jquery.com/jquery-3.6.0.js"
  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>
<?php
register_activation_hook( __FILE__, 'todo_install' );
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);


/** Step 2 (from text above). */
add_action( 'admin_menu', 'my_plugin_menu' );

/** Step 1. */
function my_plugin_menu() {
	add_menu_page( 'To Do List', 'To Do List', 'manage_options', 'list-to-do', 'my_list_to_do', 'dashicons-editor-ul');
}

/** Step 3. */
function my_list_to_do() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap" id="mydiv">';
	


	global $wpdb;
    $table_name = $wpdb->prefix . "to_do_list";
    $retrieve_data = $wpdb->get_results( "SELECT * FROM $table_name" );

    echo '<table class="table table-bordered table-striped">';
            echo "<thead>";
                echo "<tr>";
                    echo "<th>ID</th>";
                    echo "<th>Zadanie</th>";
                    echo "<th>Status</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            
			   foreach ($retrieve_data as $retrieved_data){
			   	echo "<tr>";
			        echo '<td>', $retrieved_data->id , '</td>';
			        echo '<td id=', $retrieved_data->id ,'>', $retrieved_data->task , '</td>';
			        $status = $retrieved_data->status;
				    switch ($status){
				    	case 7:
				    		$status_name = 'Nowe';
                            $status_value = 7;
				    		break;
				    	case 8:
				    		$status_name = 'W trakcie';
                            $status_value = 8;
				    		break;
				    	case 9:
				    		$status_name = 'Zakończone';
                            $status_value = 9;
				    		break;
				    }
			        echo '<td id=stat', $retrieved_data->id ,' value = ', $status_value ,'>', $status_name , '</td>';
			     echo '</tr>';
			     }
			echo "</tbody>";                            
        echo "</table>";
			        

    if (!empty($_POST)) {
        global $wpdb;
        $table = $wpdb->prefix . "to_do_list";
        $data = array(
            'task' => $_POST['task']
        );
        $success=$wpdb->insert( $table, $data );
        if($success){
            echo 'Zadanie dodane' ;

        }
    }
    ?> 
    <h2> DODAJ NOWE ZADANIE </h2>
            <form method="post" >
            <input name="task" id="tasks-unique">
            <input type="submit" id="tasks-btn" name="submit" value="DODAJ ZADANIE">
        	</form>




    <h2> OZNACZ JAKO ZAKOŃCZONE </h2>
    <?php
    echo '<form method="post" id="end">';
    echo '<select name="idd">';
    foreach ($retrieve_data as $retrieved_data){
                echo '<option value=', $retrieved_data->id ,'>', $retrieved_data->id ,'</option>';
                            
                        }
                         $idd = $_POST['idd'];
                          $update_data = $wpdb->query("UPDATE $table_name SET status = 9 WHERE ID = $idd");
    echo '</select><input type="submit" class="btn-primary" name="submit" value="ZAKOŃCZ" form="end"></form>';
                        

   
    ?>
    <h2> MODYFIKUJ ZADANIE </h2>    	
 <?php
    echo '<form method="post" id="edit">';
    echo '<select onchange="val()" name="iddd" id="iddd">';
    $arr = [];
    $arr2 = [];
    foreach ($retrieve_data as $retrieved_data){
                echo '<option value=', $retrieved_data->id ,'>', $retrieved_data->id ,'</option>';
                         array_push($arr, $retrieved_data->task); 
                         array_push($arr2, $retrieved_data->status);   
                        }

                        $iddd = $_POST['iddd'];
                        $stat = $_POST['stat'];
                        $new_task = $_POST['new_task'];
                        $update_data = $wpdb->query("UPDATE $table_name SET status = $stat, task = '$new_task' WHERE ID = $iddd");
    echo '</select>';
        $selected_new;
        $selected_going;
        $selected_end;
            if($arr2[0] == 7){
            $selected_new = 'selected';
            };
            if($arr2[0] == 8){
            $selected_going = 'selected';
            };
            if($arr2[0] == 9){
            $selected_end = 'selected';
            };
     ?>
                        <input name="new_task" id="new_task" value="<?php echo $arr[0] ?>">
                        <select name="stat" id="stat">
                            <option value="7" <?php echo $selected_new; ?> >Nowe</option>
                            <option value="8" <?php echo $selected_going; ?>>W trakcie</option>
                            <option value="9" <?php echo $selected_end; ?>>Zakończone</option>
                        </select>
                        <?php
    echo '<input type="submit" class="btn-primary" name="submit" value="MODYFIKUJ" form="edit"></form>';
                        

   
    ?>
    <h2> USUŃ ZADANIE </h2>

    <?php 
    echo '<form method="post" id="delete">';
    echo '<select name="id">';
    foreach ($retrieve_data as $retrieved_data){
                echo '<option value=', $retrieved_data->id ,'>', $retrieved_data->id ,'</option>';
                            
                        }
                         $id = $_POST['id'];
    echo '</select><input type="submit" class="btn-primary" name="submit" value="USUŃ" form="delete"></form>';
                        

    $delete_data = $wpdb->query("DELETE FROM $table_name WHERE ID = $id");


?>
        	<script type="text/javascript">
        		jQuery(document).ready(function( $ ){
        		$("#mydiv").load(location.href + " #mydiv");
        	});

        	</script>
           <script type="text/javascript">
            function val() {
            d = document.getElementById("iddd").value;
            text = document.getElementById(d).innerHTML;
            value = document.getElementById("stat" + d).attributes[1].value;
            console.log(text);
            document.getElementById("new_task").value = text;
            document.getElementById("stat").value = value;
            }
            </script>
           
             <script type="text/javascript">
            jQuery(document).ready(function() {
            $("#tasks-unique").keyup(function(event) {
                if (event.which === 13) {
                     event.preventDefault();
                    console.log("test");
                    $("#tasks-btn").click();
                }
            });
                    });   
            </script>
            <script type="text/javascript">
            jQuery(document).ready(function() {
            $("#stat").keyup(function(event) {
                if (event.which === 13) {
                     event.preventDefault();
                    console.log("test");
                    $("#tasks-btn").click();
                }
            });
                    });   
            </script>



        	<?php

	echo '</div>';
 } 


