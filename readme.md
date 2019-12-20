<html>
<head>
  <title>Delete Mysql Data using jQuery Dialogify with PHP Ajax</title>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" />
  <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" /> -->

  <script src="https://www.jqueryscript.net/demo/Dialog-Modal-Dialogify/dist/dialogify.min.js"></script>

  <!-- datepicker  -->
  <link rel="stylesheet" type="text/css" href="../jquery.dataTables.min.css">
  <script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


</head>
<body>
  <div class="container">
   <br />
   <!-- <h3 align="center">Delete Mysql Data using jQuery Dialogify with PHP Ajax</h3> -->
   <br />
   <div class="panel panel-default">
    <div class="panel-heading">
     <div class="row">
      <div class="col-md-6">
       <h3 class="panel-title">員工資料</h3>
     </div>
     <div class="col-md-6" align="right">
       <button type="button" name="add_data" id="add_data" class="btn btn-success btn-xs">新增員工</button>
     </div>
   </div>
 </div>
 <div class="panel-body">
   <div class="table-responsive">
    <span id="form_response"></span>
    <div class="pb-3">
      <tr>
        <td>起始日期:</td>
        <td><input type="text" id="min" name="min"></td>
      </tr>
      <tr>
        <td>結束日期:</td>
        <td><input type="text" id="max" name="max"></td>
      </tr>
    </div>
    <table id="user_data" class="table table-bordered table-striped" width="100%">
     <thead>
      <tr>
       <td>姓名</td>
       <td>性別</td>
       <td>職位</td>
       <td>年紀</td>
       <td>入職日期</td>
       <td>查看</td>
       <td>編輯</td>
       <td>刪除</td>
     </tr>
   </thead>
 </table>      
</div>
</div>
</div>
</div>
</body>
</html>

<script type="text/javascript" language="javascript" >
  $(document).ready(function(){

   $.fn.dataTable.ext.search.push(
     function(settings, data, dataIndex) {
       var min = $('#min').datepicker("getDate");
       var max = $('#max').datepicker("getDate");
       var startDate = new Date(data[4]);
       if (min == null && max == null) {
         return true;
       }
       if (min == null && startDate <= max) {
         return true;
       }
       if (max == null && startDate >= min) {
         return true;
       }
       if (startDate <= max && startDate >= min) {
         return true;
       }
       return false;
     }
     );


   $("#min").datepicker({
     onSelect: function() {
       table.draw();
     },
     changeMonth: true,
     changeYear: true
   });
   $("#max").datepicker({
     onSelect: function() {
       table.draw();
     },
     changeMonth: true,
     changeYear: true
   });
   var table = $('#example').DataTable();

   /*Event listener to the two range filtering inputs to redraw on input*/
   $('#min, #max').change(function() {
     table.draw();
   });

   var dataTable = $('#user_data').DataTable({
    "processing":true,
    "serverSide":true,
    "order":[],
    "ajax":{
     url:"fetch.php",
     type:"POST"
   },
   "columnDefs":[
   {
    "targets":[5,6,7],
    "orderable":false,
  },
  ],
  "language": {
    "url": "../Chinese-traditional.json" 
  }
});



   $(document).on('click', '.view', function(){
    var id = $(this).attr('id');
    var options = {
     ajaxPrefix: '',
     ajaxData: {id:id},
     ajaxComplete:function(){
      this.buttons([{
       type: Dialogify.BUTTON_PRIMARY
     }]);
    }
  };
  new Dialogify('fetch_single.php', options)
  .title('查看員工資料')
  .showModal();
});

   $('#add_data').click(function(){
    var options = {
     ajaxPrefix:''
   };
   new Dialogify('add_data_form.php', options)
   .title('新增資料')
   .buttons([
   {
     text:'Cancle',
     click:function(e){
      this.close();
    }
  },
  {
   text:'Insert',
   type:Dialogify.BUTTON_PRIMARY,
   click:function(e)
   {
    var image_data = $('#images').prop("files")[0];
    var form_data = new FormData();
    form_data.append('images', image_data);
    form_data.append('name', $('#name').val());
    form_data.append('address', $('#address').val());
    form_data.append('gender', $('#gender').val());
    form_data.append('designation', $('#designation').val());
    form_data.append('age', $('#age').val());
    form_data.append('Years', $('#Years').val());
    $.ajax({
     method:"POST",
     url:'insert_data.php',
     data:form_data,
     dataType:'json',
     contentType:false,
     cache:false,
     processData:false,
     success:function(data)
     {
      if(data.error != '')
      {
       $('#form_response').html('<div class="alert alert-danger">'+data.error+'</div>');
     }
     else
     {
       $('#form_response').html('<div class="alert alert-success">'+data.success+'</div>');
       dataTable.ajax.reload();
     }
   }
 });
  }
}
]).showModal();
 });

   $(document).on('click', '.update', function(){
    var id = $(this).attr('id');
    $.ajax({
     url:"fetch_single_data.php",
     method:"POST",
     data:{id:id},
     dataType:'json',
     success:function(data)
     {
      localStorage.setItem('name', data[0].name);
      localStorage.setItem('address', data[0].address);
      localStorage.setItem('gender', data[0].gender);
      localStorage.setItem('designation', data[0].designation);
      localStorage.setItem('age', data[0].age);
      localStorage.setItem('Years', data[0].Years);
      localStorage.setItem('images', data[0].images);

      var options = {
       ajaxPrefix:''
     };
     new Dialogify('edit_data_form.php', options)
     .title('Edit Employee Data')
     .buttons([
     {
       text:'Cancle',
       click:function(e){
        this.close();
      }
    },
    {
     text:'Edit',
     type:Dialogify.BUTTON_PRIMARY,
     click:function(e)
     {
      var image_data = $('#images').prop("files")[0];
      var form_data = new FormData();
      form_data.append('images', image_data);
      form_data.append('name', $('#name').val());
      form_data.append('address', $('#address').val());
      form_data.append('gender', $('#gender').val());
      form_data.append('designation', $('#designation').val());
      form_data.append('age', $('#age').val());
      form_data.append('Years', $('#Years').val());
      form_data.append('hidden_images', $('#hidden_images').val());
      form_data.append('id', data[0].id);
      $.ajax({
       method:"POST",
       url:'update_data.php',
       data:form_data,
       dataType:'json',
       contentType:false,
       cache:false,
       processData:false,
       success:function(data)
       {
        if(data.error != '')
        {
         $('#form_response').html('<div class="alert alert-danger">'+data.error+'</div>');
       }
       else
       {
         $('#form_response').html('<div class="alert alert-success">'+data.success+'</div>');
         dataTable.ajax.reload();
       }
     }
   });
    }
  }
  ]).showModal();
   }
 })
  });

   $(document).on('click', '.delete', function(){
    var id = $(this).attr('id');
    Dialogify.confirm("<h3 class='text-danger'><b>確定要刪除?</b></h3>", {
     ok:function(){
      $.ajax({
       url:"delete_data.php",
       method:"POST",
       data:{id:id},
       success:function(data)
       {
        Dialogify.alert('<h3 class="text-success text-center"><b>刪除成功</b></h3>');
        dataTable.ajax.reload();
      }
    })
    },
    cancel:function(){
      this.close();
    }
  });
  });


 });
</script>
