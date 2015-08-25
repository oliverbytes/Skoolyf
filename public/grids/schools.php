<script>

  $(function()
  {
    var last_clicked_id = 0;
    var lastSel = 0;

    function accessFormat( cellvalue, options, rowObject )
    {
      if(cellvalue == 1)
      {
        return "ENABLED";
      }
      else if(cellvalue == 0)
      {
        return "DISABLED";
      }
    }

    function statusFormat( cellvalue, options, rowObject )
    {
      if(cellvalue == 1)
      {
        return "PENDING";
      }
      else if(cellvalue == 0)
      {
        return "APPROVED";
      }
    }

    function  imageFormat(cellvalue, options, rowObject)
    {
      if(cellvalue)
      {
       return "<img src='data:image/jpeg;base64, "+cellvalue+"' style='height:25px;' />";
      }
      else
      {
        return "NONE";
      }
    }

    jQuery("#grid_schools").jqGrid({
        url:'public/grids/schools_xml.php',
        datatype: 'xml',
        mtype: 'GET',
        colNames:[
        'ACTION', 
        'ID', 
        'NAME', 
        'EMAIL', 
        'NUMBER', 
        'ABOUT', 
        'ADDRESS', 
        'LOGO',
        'COVER',  
        'DATE CREATED',
        'COMMENTS',
        'STATUS',
        'ACCESS'
        ],
        colModel :[ 
          {name:'act',index:'act', width:6,sortable:false, search: false},
          {name:'id', index:'id', width:3, align:'left', sortable:true, search:true},
          {name:'name', index:'name', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'email', index:'email', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'number', index:'number', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'about', index:'about', width:15, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'address', index:'address', width:15, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'logo', index:'logo', width:3, align:'left', sortable:true, editable:true, formatter:imageFormat, editoptions: {size:30}, search:true},
          {name:'cover', index:'cover', width:3, align:'left', sortable:true, editable:true, formatter:imageFormat, editoptions: {size:30}, search:true},
          {name:'date', index:'date', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'comments', index:'comments', width:5, align:'left', sortable:true, editable:true, search:true, formatter:accessFormat, edittype:'select', editoptions:{value:{1:'ENABLED', 0:'DISABLED'}}},
          {name:'pending', index:'pending', width:5, align:'left', sortable:true, editable:true, search:true, formatter:statusFormat, edittype:'select', editoptions:{value:{1:'PENDING', 0:'APPROVED'}}},
          {name:'enabled', index:'enabled', width:5, align:'left', sortable:true, editable:true, search:true, formatter:accessFormat, edittype:'select', editoptions:{value:{1:'ENABLED', 0:'DISABLED'}}}
        ],
        width: 1300,
        height: 400,
        pager: '#nav_schools',
        rowNum:30,
        rowList:[10,20,30,40,50,100,200,300,400,500],
        sortname: 'id',
        sortorder: 'desc',
        gridComplete: function()
        {
          var ids = jQuery("#grid_schools").jqGrid('getDataIDs');
          for(var i=0;i < ids.length;i++)
          {
            var id = ids[i];
            edit = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_schools').editGridRow('"+id+"', {width:300}); jQuery('#grid_schools').trigger('reloadGrid');\"><span class='ui-icon ui-icon-pencil'></span></button>"; 
            del = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_schools').delGridRow('"+id+"'); jQuery('#grid_schools').trigger('reloadGrid');\"><span class='ui-icon ui-icon-trash'></span></button>";
            save = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_schools').saveRow('"+id+"'); jQuery('#grid_schools').trigger('reloadGrid');\" ><span class='ui-icon ui-icon-check'></span></button>"; 
            jQuery("#grid_schools").jqGrid('setRowData',ids[i],{act:edit+del+save});
          }
        },
        editurl: "public/grids/schools_manipulate.php",
        viewrecords: true,
        gridview: true,
        caption: 'schools',
        multiselect:true,
        onSelectRow: function(id)
        {
         if(id && id!==lastSel)
         { 
            jQuery('#grid_schools').restoreRow(lastSel); 
            lastSel=id; 
         }
         jQuery('#grid_schools').editRow(id);
       }
    });

  jQuery("#grid_schools").jqGrid('navGrid','#nav_schools',{edit:true, add:true, del:true}).
    navButtonAdd('#nav_schools',{
       caption:"Delete", 
       buttonicon:"ui-icon-circle-minus", 
       onClickButton: function(){

          var ids = jQuery("#grid_schools").jqGrid('getGridParam','selarrrow');

          if(ids.length > 0)
          {
            if(confirm("Delete selected schools?"))
            {
              $.ajax({
                type:"POST",
                url:"public/grids/multi_delete.php",
                data: {ids:ids, what:"school"},
                success: function(result)
                {
                    if(result == "success")
                    {
                        //playSound("positive");
                        jQuery("#grid_schools").trigger("reloadGrid");
                        return false;
                    }
                    else
                    {
                        //playSound("negative");
                        bootbox.alert(result);
                        return false;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    //playSound("negative");
                    bootbox.alert("error");
                    return false;
                }
              });
            }
          }
          else
          {
            //playSound("negative");
            bootbox.alert("please select atleast one");
          }
          return false;
       },
       position:"last"
    });
});

</script>

<table id="grid_schools"><tr><td/></tr></table> 
<div id="nav_schools"></div>