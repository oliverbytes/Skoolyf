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
      else
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
      else
      {
        return "APPROVED";
      }
    }

    function userLevelFormat( cellvalue, options, rowObject )
    {
      if(cellvalue == 1)
      {
        return "ADMIN";
      }
      else if(cellvalue == 0)
      {
        return "USER";
      }
    }

    jQuery("#grid_sectionusers").jqGrid({
        url:'public/grids/sectionusers_xml.php',
        datatype: 'xml',
        mtype: 'GET',
        colNames:[
        'ACTION', 
        'ID', 
        'USER ID', 
        'USER NAME', 
        'SCHOOL ID', 
        'SCHOOL NAME', 
        'BATCH ID', 
        'BATCH YEAR', 
        'SECTION ID', 
        'SECTION NAME', 
        'LEVEL', 
        'DATE CREATED', 
        'STATUS',
        'ENABLED'
        ],
        colModel :[ 
          {name:'act',index:'act', width:7,sortable:false, search: false},
          {name:'id', index:'id', width:3, align:'left', sortable:true, search:true},
          {name:'userid', index:'userid', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'username', index:'username', width:10, align:'left', sortable:true, editable:false, editoptions: {size:30}, search:true},
          {name:'schoolid', index:'schoolid', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'schoolname', index:'schoolname', width:10, align:'left', sortable:true, editable:false, editoptions: {size:30}, search:true},
          {name:'batchid', index:'batchid', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'batchyear', index:'batchyear', width:5, align:'left', sortable:true, editable:false, editoptions: {size:30}, search:true},
          {name:'sectionid', index:'sectionid', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'sectionname', index:'sectionname', width:5, align:'left', sortable:true, editable:false, editoptions: {size:30}, search:true},
          {name:'level', index:'level', width:5, align:'left', sortable:true, editable:true, search:true, formatter:userLevelFormat, edittype:'select', editoptions:{value:{1:'ADMIN', 0:'USER'}}},
          {name:'date', index:'date', width:10, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'pending', index:'pending', width:5, align:'left', sortable:true, editable:true, search:true, formatter:statusFormat, edittype:'select', editoptions:{value:{1:'PENDING', 0:'APPROVED'}}},
          {name:'enabled', index:'enabled', width:5, align:'left', sortable:true, editable:true, search:true, formatter:accessFormat, edittype:'select', editoptions:{value:{1:'ENABLED', 0:'DISABLED'}}}
        ],
        width: 1300,
        height: 400,
        pager: '#nav_sectionusers',
        rowNum:30,
        rowList:[10,20,30,40,50,100,200,300,400,500],
        sortname: 'id',
        sortorder: 'desc',
        gridComplete: function()
        {
          var ids = jQuery("#grid_sectionusers").jqGrid('getDataIDs');
          for(var i=0;i < ids.length;i++)
          {
            var id = ids[i];
            edit = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_sectionusers').editGridRow('"+id+"', {width:300}); jQuery('#grid_sectionusers').trigger('reloadGrid');\"><span class='ui-icon ui-icon-pencil'></span></button>"; 
            del = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_sectionusers').delGridRow('"+id+"'); jQuery('#grid_sectionusers').trigger('reloadGrid');\"><span class='ui-icon ui-icon-trash'></span></button>";
            save = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_sectionusers').saveRow('"+id+"'); jQuery('#grid_sectionusers').trigger('reloadGrid');\" ><span class='ui-icon ui-icon-check'></span></button>"; 
            jQuery("#grid_sectionusers").jqGrid('setRowData',ids[i],{act:edit+del+save});
          }
        },
        editurl: "public/grids/sectionusers_manipulate.php",
        viewrecords: true,
        gridview: true,
        caption: 'sectionusers',
        multiselect:true,
        onSelectRow: function(id)
        {
         if(id && id!==lastSel)
         { 
            jQuery('#grid_sectionusers').restoreRow(lastSel); 
            lastSel=id; 
         }
         jQuery('#grid_sectionusers').editRow(id);
       }
    });

  jQuery("#grid_sectionusers").jqGrid('navGrid','#nav_sectionusers',{edit:true, add:true, del:true}).
    navButtonAdd('#nav_sectionusers',{
       caption:"Delete", 
       buttonicon:"ui-icon-circle-minus", 
       onClickButton: function(){

          var ids = jQuery("#grid_sectionusers").jqGrid('getGridParam','selarrrow');

          if(ids.length > 0)
          {
            if(confirm("Delete selected sectionusers?"))
            {
              $.ajax({
                type:"POST",
                url:"public/grids/multi_delete.php",
                data: {ids:ids, what:"sectionuser"},
                success: function(result)
                {
                    if(result == "success")
                    {
                        //playSound("positive");
                        jQuery("#grid_sectionusers").trigger("reloadGrid");
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
    }).
    navButtonAdd('#nav_sectionusers',{
       caption:"Disable", 
       buttonicon:"ui-icon-circle-close", 
       onClickButton: function(){

          var ids = jQuery("#grid_sectionusers").jqGrid('getGridParam','selarrrow');

          if(ids.length > 0)
          {
            if(confirm("Disable selected sectionusers?"))
            {
              $.ajax({
                type:"POST",
                url:"public/grids/multi_disable.php",
                data: {ids:ids},
                success: function(result)
                {
                    if(result == "success")
                    {
                        //playSound("positive");
                        jQuery("#grid_sectionusers").trigger("reloadGrid");
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
            bootbox.alert("please select atleast one");
          }
          return false;
       },
       position:"last"
    }).
    navButtonAdd('#nav_sectionusers',{
       caption:"Enable", 
       buttonicon:"ui-icon-circle-check", 
       onClickButton: function(){

          var ids = jQuery("#grid_sectionusers").jqGrid('getGridParam','selarrrow');

          if(ids.length > 0)
          {
            if(confirm("Enable selected sectionusers?"))
            {
              $.ajax({
                type:"POST",
                url:"public/grids/multi_enable.php",
                data: {ids:ids},
                success: function(result)
                {
                    if(result == "success")
                    {
                        //playSound("positive");
                        jQuery("#grid_sectionusers").trigger("reloadGrid");
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

<table id="grid_sectionusers"><tr><td/></tr></table> 
<div id="nav_sectionusers"></div>