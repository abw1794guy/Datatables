<?php

//fetch_single.php

include('database_connection.php');

if(isset($_GET["id"]))
{
 $query = "SELECT * FROM tbl_employee WHERE id = '".$_GET["id"]."'";

 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 $output = '<div class="row">';
 foreach($result as $row)
 {
  $images = '';
  if($row["images"] != '')
  // {
  //  $images = '<img src="images/'.$row["images"].'" class="img-responsive img-thumbnail" />';
  // }
  // else
  {
   $images = '<img src="https://www.gravatar.com/avatar/38ed5967302ec60ff4417826c24feef6?s=80&d=mm&r=g" class="img-responsive img-thumbnail" />';
  }
  $output .= '
  <div class="col-md-3">
   <br />
   '.$images.'
  </div>
  <div class="col-md-9">
   <br />
   <p><label>姓名 :&nbsp;</label>'.$row["name"].'</p>
   <p><label>地址 :&nbsp;</label>'.$row["address"].'</p>
   <p><label>性別 :&nbsp;</label>'.$row["gender"].'</p>
   <p><label>職別 :&nbsp;</label>'.$row["designation"].'</p>
   <p><label>年齡 :&nbsp;</label>'.$row["age"].' years</p>
   <p><label>入職日 :&nbsp;</label>'.$row["Years"].'</p>
  </div>
  </div><br />
  ';
 }
 echo $output;
}

?>
