<script>

  $(function()
  {
    var last_clicked_id = 0;

    var lastSel = 0;

    jQuery("#grid_hits").jqGrid({
        url:'public/grids/hits_xml.php',
        datatype: 'xml',
        mtype: 'GET',
        colNames:[
        'ACTION', 
        'NAME', 
        'PLATFORM', 
        'USER_ID', 
        'USERNAME', 
        'DATE'
        ],
        colModel :[ 
          {name:'act',index:'act', width:3,sortable:false, search: false},
          {name:'name', index:'name', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'platform', index:'platform', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'user_id', index:'user_id', width:3, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'username', index:'username', width:5, align:'left', sortable:true, editable:false, editoptions: {size:30}, search:true},
          {name:'date', index:'date', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true}
        ],
        width: 1400,
        height: 400,
        pager: '#nav_hits',
        rowNum:30,
        rowList:[10,20,30,40,50,100,200,300,400,500],
        sortname: 'id',
        sortorder: 'desc',
        gridComplete: function()
        {
          var ids = jQuery("#grid_hits").jqGrid('getDataIDs');
          for(var i=0;i < ids.length;i++)
          {
            var id = ids[i];
            edit = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_hits').editGridRow('"+id+"', {width:300}); jQuery('#grid_hits').trigger('reloadGrid');\"><span class='ui-icon ui-icon-pencil'></span></button>"; 
            del = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_hits').delGridRow('"+id+"'); jQuery('#grid_hits').trigger('reloadGrid');\"><span class='ui-icon ui-icon-trash'></span></button>";
            save = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_hits').saveRow('"+id+"'); jQuery('#grid_hits').trigger('reloadGrid');\" ><span class='ui-icon ui-icon-check'></span></button>"; 
            jQuery("#grid_hits").jqGrid('setRowData',ids[i],{act:edit+del+save});
          }
        },
        editurl: "public/grids/hits_manipulate.php",
        viewrecords: true,
        gridview: true,
        caption: 'hits',
        multiselect:true,
        onSelectRow: function(id)
        {
         if(id && id!==lastSel)
         { 
            jQuery('#grid_hits').restoreRow(lastSel); 
            lastSel=id; 
         }
         jQuery('#grid_hits').editRow(id);
       }
    });

  jQuery("#grid_hits").jqGrid('navGrid','#nav_hits',{edit:true, add:true, del:true}).
    navButtonAdd('#nav_hits',{
       caption:"Delete Selected", 
       buttonicon:"ui-icon-add", 
       onClickButton: function(){
          var ids = jQuery("#grid_hits").jqGrid('getGridParam','selarrrow');
          if(ids.length > 0)
          {
            if(confirm("Delete selected records?"))
            {
              $.ajax({
                type:"POST",
                url:"public/grids/multi_delete.php",
                data: {ids:ids, what:"hit"},
                success: function(result)
                {
                    if(result == "success")
                    {
                        //playSound("positive");
                        jQuery("#grid_hits").trigger("reloadGrid");
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

<table id="grid_hits"><tr><td/></tr></table> 
<div id="nav_hits"></div>