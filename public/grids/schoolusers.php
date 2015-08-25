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

    jQuery("#grid_schoolusers").jqGrid({
        url:'public/grids/schoolusers_xml.php',
        datatype: 'xml',
        mtype: 'GET',
        colNames:[
        'ACTION', 
        'ID', 
        'SCHOOL ID', 
        'SCHOOL NAME', 
        'USER ID', 
        'USER NAME', 
        'LEVEL', 
        'DATE CREATED',
        'STATUS',
        'ACCESS'
        ],
        colModel :[ 
          {name:'act',index:'act', width:6,sortable:false, search: false},
          {name:'id', index:'id', width:5, align:'left', sortable:true, search:true},
          {name:'schoolid', index:'schooluserid', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'schoolname', index:'schoolname', width:10, align:'left', sortable:true, editable:false, editoptions: {size:30}, search:true},
          {name:'userid', index:'userid', width:5, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'username', index:'username', width:10, align:'left', sortable:true, editable:false, editoptions: {size:30}, search:true},
          {name:'level', index:'level', width:5, align:'left', sortable:true, editable:true, search:true, formatter:userLevelFormat, edittype:'select', editoptions:{value:{1:'ADMIN', 0:'USER'}}},
          {name:'date', index:'date', width:10, align:'left', sortable:true, editable:true, editoptions: {size:30}, search:true},
          {name:'pending', index:'pending', width:5, align:'left', sortable:true, editable:true, search:true, formatter:statusFormat, edittype:'select', editoptions:{value:{1:'PENDING', 0:'APPROVED'}}},
          {name:'enabled', index:'enabled', width:5, align:'left', sortable:true, editable:true, search:true, formatter:accessFormat, edittype:'select', editoptions:{value:{1:'ENABLED', 0:'DISABLED'}}}
        ],
        width: 1200,
        height: 400,
        pager: '#nav_schoolusers',
        rowNum:30,
        rowList:[10,20,30,40,50,100,200,300,400,500],
        sortname: 'id',
        sortorder: 'desc',
        gridComplete: function()
        {
          var ids = jQuery("#grid_schoolusers").jqGrid('getDataIDs');
          for(var i=0;i < ids.length;i++)
          {
            var id = ids[i];
            edit = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_schoolusers').editGridRow('"+id+"', {width:300}); jQuery('#grid_schoolusers').trigger('reloadGrid');\"><span class='ui-icon ui-icon-pencil'></span></button>"; 
            del = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_schoolusers').delGridRow('"+id+"'); jQuery('#grid_schoolusers').trigger('reloadGrid');\"><span class='ui-icon ui-icon-trash'></span></button>";
            save = "<button class='ui-state-default ui-corner-all' onclick=\"jQuery('#grid_schoolusers').saveRow('"+id+"'); jQuery('#grid_schoolusers').trigger('reloadGrid');\" ><span class='ui-icon ui-icon-check'></span></button>"; 
            jQuery("#grid_schoolusers").jqGrid('setRowData',ids[i],{act:edit+del+save});
          }
        },
        editurl: "public/grids/schoolusers_manipulate.php",
        viewrecords: true,
        gridview: true,
        caption: 'schoolusers',
        multiselect:true,
        onSelectRow: function(id)
        {
         if(id && id!==lastSel)
         { 
            jQuery('#grid_schoolusers').restoreRow(lastSel); 
            lastSel=id; 
         }
         jQuery('#grid_schoolusers').editRow(id);
       }
    });

  jQuery("#grid_schoolusers").jqGrid('navGrid','#nav_schoolusers',{edit:true, add:true, del:true}).
    navButtonAdd('#nav_schoolusers',{
       caption:"Delete", 
       buttonicon:"ui-icon-circle-minus", 
       onClickButton: function(){

          var ids = jQuery("#grid_schoolusers").jqGrid('getGridParam','selarrrow');

          if(ids.length > 0)
          {
            if(confirm("Delete selected schoolusers?"))
            {
              $.ajax({
                type:"POST",
                url:"public/grids/multi_delete.php",
                data: {ids:ids, what:"schooluser"},
                success: function(result)
                {
                    if(result == "success")
                    {
                        //playSound("positive");
                        jQuery("#grid_schoolusers").trigger("reloadGrid");
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
    navButtonAdd('#nav_schoolusers',{
       caption:"Disable", 
       buttonicon:"ui-icon-circle-close", 
       onClickButton: function(){

          var ids = jQuery("#grid_schoolusers").jqGrid('getGridParam','selarrrow');

          if(ids.length > 0)
          {
            if(confirm("Disable selected schoolusers?"))
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
                        jQuery("#grid_schoolusers").trigger("reloadGrid");
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
    navButtonAdd('#nav_schoolusers',{
       caption:"Enable", 
       buttonicon:"ui-icon-circle-check", 
       onClickButton: function(){

          var ids = jQuery("#grid_schoolusers").jqGrid('getGridParam','selarrrow');

          if(ids.length > 0)
          {
            if(confirm("Enable selected schoolusers?"))
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
                        jQuery("#grid_schoolusers").trigger("reloadGrid");
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

<table id="grid_schoolusers"><tr><td/></tr></table> 
<div id="nav_schoolusers"></div>