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

    jQuery("#grid_batchs").jqGrid({
        url:'public/grids/batchs_xml.php',
        datatype: 'xml',
        mtype: 'GET',
        colNames:[
        'ACTION', 
        'ID', 
        'FROM YEAR', 
        'TO YEAR', 
        'ABOUT', 
        'SCHOOL ID', 
        'SCHOOL NAME', 
        'DATE CREATED',
        'COMMENTS',
        'STATUS',
        'ACCESS'
        ],
        colModel :[ 
          {name:'act',index:'act', width:7,sortable:false, search: false},
          {name:'id', index:'id', width:3, align:'left', sortable:true, search:true},
          {name:'fromyear', index:'batchname', width:3, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'toyear', index:'toyear', width:3, align:'left', sortable:true, editable:false, editoptions: {size:30}, search:true},
          {name:'about', index:'about', width:10, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'schoolid', index:'schoolid', width:3, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'schoolname', index:'schoolname', width:10, align:'left', sortable:true, editable:false, editoptions: {size:30}, search:true},
          {name:'date', index:'date', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'comments', index:'comments', width:5, align:'left', sortable:true, editable:true, search:true, formatter:accessFormat, edittype:'select', editoptions:{value:{1:'ENABLED', 0:'DISABLED'}}},
          {name:'pending', index:'pending', width:5, align:'left', sortable:true, editable:true, search:true, formatter:statusFormat, edittype:'select', editoptions:{value:{1:'PENDING', 0:'APPROVED'}}},
          {name:'enabled', index:'enabled', width:5, align:'left', sortable:true, editable:true, search:true, formatter:accessFormat, edittype:'select', editoptions:{value:{1:'ENABLED', 0:'DISABLED'}}}
        ],
        width: 1300,
        height: 400,
        pager: '#nav_batchs',
        rowNum:30,
        rowList:[10,20,30,40,50,100,200,300,400,500],
        sortname: 'id',
        sortorder: 'desc',
        gridComplete: function()
        {
          var ids = jQuery("#grid_batchs").jqGrid('getDataIDs');
          for(var i=0;i < ids.length;i++)
          {
            var id = ids[i];
            edit = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_batchs').editGridRow('"+id+"', {width:300}); jQuery('#grid_batchs').trigger('reloadGrid');\"><span class='ui-icon ui-icon-pencil'></span></button>"; 
            del = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_batchs').delGridRow('"+id+"'); jQuery('#grid_batchs').trigger('reloadGrid');\"><span class='ui-icon ui-icon-trash'></span></button>";
            save = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_batchs').saveRow('"+id+"'); jQuery('#grid_batchs').trigger('reloadGrid');\" ><span class='ui-icon ui-icon-check'></span></button>"; 
            jQuery("#grid_batchs").jqGrid('setRowData',ids[i],{act:edit+del+save});
          }
        },
        editurl: "public/grids/batchs_manipulate.php",
        viewrecords: true,
        gridview: true,
        caption: 'batchs',
        multiselect:true,
        onSelectRow: function(id)
        {
         if(id && id!==lastSel)
         { 
            jQuery('#grid_batchs').restoreRow(lastSel); 
            lastSel=id; 
         }
         jQuery('#grid_batchs').editRow(id);
       }
    });

  jQuery("#grid_batchs").jqGrid('navGrid','#nav_batchs',{edit:true, add:true, del:true}).
    navButtonAdd('#nav_batchs',
    {
       caption:"Delete", 
       buttonicon:"ui-icon-circle-minus", 
       onClickButton: function()
       {
          var ids = jQuery("#grid_batchs").jqGrid('getGridParam','selarrrow');

          if(ids.length > 0)
          {
            if(confirm("Delete selected batchs?"))
            {
              $.ajax(
              {
                type:"POST",
                url:"public/grids/multi_delete.php",
                data: {ids:ids, what:"batch"},
                success: function(result)
                {
                    if(result == "success")
                    {
                      //playSound("positive");
                      jQuery("#grid_batchs").trigger("reloadGrid");
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

<table id="grid_batchs"><tr><td/></tr></table> 
<div id="nav_batchs"></div>