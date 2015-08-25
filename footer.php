    <hr>

    </div><!--/.fluid-container-->
    <script> $.ajaxSetup({ cache: false }); </script>
    <script>

    $.toast.config.align = 'right';
    $.toast.config.closeForStickyOnly = false;
    $.toast.config.width  = 200;

    function showToast(message, type)
    {
      var options = 
      {
        duration: 3000,
        sticky: false,
        type: type
      };

      $.toast(message, options);
    }

    $(document).ready(function()
    {

      $('tbody.rowlink').rowlink();

      $(function () 
      { 
        $("#regpopover").tooltip('show');
      });

      var items = [];
      var map = {};

      $('.search-query').typeahead(
      {
        items: 15,
        source: function (query, process) 
        {
          $.ajax(
          {
            type: 'POST',
            url: 'includes/webservices/search.php',
            data: {input: query},
            success: function(result) 
            {
              if(result != "")
              {
                var jsonArray = JSON.parse(result);

                if($.isArray(jsonArray))
                {
                  for(index = 0; index < jsonArray.length; index++) // tables array
                  {
                    var jsonObjects = jsonArray[index].objects;
                    var tableName   = jsonArray[index].name;

                    if(tableName == "users")
                    {
                      $.each(jsonObjects, function (i, object)
                      {
                        thisObject            = new Object();
                        thisObject.tableName  = tableName;
                        thisObject.object     = object;

                        map[object.username + " " + object.firstname + " " + object.middlename + " " + object.lastname] = thisObject;
                        items.push(object.username + " " + object.firstname + " " + object.middlename + " " + object.lastname);
                      });
                    }
                    else
                    {
                      if(jsonObjects.length > 0){items.push("separator");}

                      $.each(jsonObjects, function (i, object)
                      {
                        thisObject            = new Object();
                        thisObject.tableName  = tableName;
                        thisObject.object     = object;

                        map[object.name]      = thisObject;
                        items.push(object.name);
                      });
                    }
                  }
                }
              }

              process(items);
              items.length = 0;
            }
          });
        },
        updater: function (item) 
        {
          var object = map[item].object;
          var tableName = map[item].tableName;

          if(tableName == "users")
          {
            window.location.href = "student.php?id="+ object.id;
          }
          else if(tableName == "schools")
          {
            window.location.href = "school.php?id="+ object.id;
          }
          else if(tableName == "batchs")
          {
            window.location.href = "batch.php?id="+ object.id;
          }
          else if(tableName == "sections")
          {
            window.location.href = "section.php?id="+ object.id;
          }
          else if(tableName == "clubs")
          {
            window.location.href = "club.php?id="+ object.id;
          }
          else if(tableName == "groups")
          {
            window.location.href = "group.php?id="+ object.id;
          }
        },
        highlighter: function(item)
        {
            var object = map[item].object;
            var tableName = map[item].tableName;

            var listItem = "";

            if(tableName == "users")
            {
              listItem = ''
                     + "<div class='typeahead_wrapper'>"
                     + "<img class='typeahead_photo' src='data:image/jpeg;base64, " + object.picture + "' />"
                     + "<div class='typeahead_labels'>"
                     + "<div class='typeahead_primary'>" + object.firstname + " " + object.middlename[0] + ". " + object.lastname + "</div>"
                     + "<div class='typeahead_secondary'>user</div>"
                     + "</div>";
            }
            else if(tableName == "schools")
            {
              listItem = ''
                     + "<div class='typeahead_wrapper'>"
                     + "<img class='typeahead_photo' src='data:image/jpeg;base64, " + object.logo + "' />"
                     + "<div class='typeahead_labels'>"
                     + "<div class='typeahead_primary'>" + object.name + "</div>"
                     + "<div class='typeahead_secondary'>school</div>"
                     + "</div>";
            }
            else if(tableName == "batchs")
            {
              var batchyear = object.fromyear + "-" + (parseInt(object.fromyear) + 1);

              listItem = ''
                     + "<div class='typeahead_wrapper'>"
                     + "<img class='typeahead_photo' src='data:image/jpeg;base64, " + object.picture + "' />"
                     + "<div class='typeahead_labels'>"
                     + "<div class='typeahead_primary'>" + batchyear + "</div>"
                     + "<div class='typeahead_secondary'>batch</div>"
                     + "</div>";
            }
            else if(tableName == "sections")
            {
              listItem = ''
                     + "<div class='typeahead_wrapper'>"
                     + "<img class='typeahead_photo' src='data:image/jpeg;base64, " + object.picture + "' />"
                     + "<div class='typeahead_labels'>"
                     + "<div class='typeahead_primary'>" + object.name + "</div>"
                     + "<div class='typeahead_secondary'>section</div>"
                     + "</div>";
            }
            else if(tableName == "clubs")
            {
              listItem = ''
                     + "<div class='typeahead_wrapper'>"
                     + "<img class='typeahead_photo' src='data:image/jpeg;base64, " + object.logo + "' />"
                     + "<div class='typeahead_labels'>"
                     + "<div class='typeahead_primary'>" + object.name + "</div>"
                     + "<div class='typeahead_secondary'>club</div>"
                     + "</div>";
            }
            else if(tableName == "groups")
            {
              listItem = ''
                     + "<div class='typeahead_wrapper'>"
                     + "<img class='typeahead_photo' src='data:image/jpeg;base64, " + object.logo + "' />"
                     + "<div class='typeahead_labels'>"
                     + "<div class='typeahead_primary'>" + object.name + "</div>"
                     + "<div class='typeahead_secondary'>group</div>"
                     + "</div>";
            }
            
            return listItem;
        }
      });

      $('.search-query').typeahead.Constructor.prototype.render = function (items) 
      {
        var that = this;

        items = $(items).map(function (i, item) 
        {
          var elements = [];

          if (item === "separator") 
          {
            elements.push($("<li/>").addClass("divider")[0]);
          }
          else
          {
            i = $(that.options.item).attr('data-value', item);
            i.find('a').html(that.highlighter(item));
            elements.push(i[0]);
          }

          return elements;
        });

        items.first().addClass('active');
        this.$menu.html(items);

        return this;
      }

      var audioElement  = document.createElement('audio');
      var message       = "<?php echo $message; ?>";
      var sound         = "<?php echo $sound; ?>";
      var currentFile   = "<?php echo $currentFile; ?>";

      if(message != "")
      {
        bootbox.alert(message);
      }

      if(sound != "")
      {
        playSound(sound);
      }

      if(currentFile == "index")
      {
        $("#index").addClass("active");
        document.title = 'skoolyf';
      }
      else if(currentFile == "editor")
      {
        $("#mybatchsdropdown").addClass("active");
        document.title = 'Editor - skoolyf';
      }
      else if(currentFile == "about")
      {
        $("#about").addClass("active");
        document.title = 'About - skoolyf';
      }
      else if(currentFile == "cpanel")
      {
        $("#cpanel").addClass("active");
        document.title = 'CPanel - skoolyf';
      }
      else if(currentFile == "contact")
      {
        $("#contact").addClass("active");
        document.title = 'Contact Us - skoolyf';
      }
      else if(currentFile == "developers")
      {
        $("#developers").addClass("active");
        document.title = 'Developers - skoolyf';
      }
      else if(currentFile == "createuser")
      {
        $("#createuser").addClass("active");
        $("#createdropdown").addClass("active");
        document.title = 'Create User - skoolyf';
      }
      else if(currentFile == "createschool")
      {
        $("#createschool").addClass("active");
        $("#createdropdown").addClass("active");
        document.title = 'Create School - skoolyf';
      }
      else if(currentFile == "createbatch")
      {
        $("#createbatch").addClass("active");
        $("#createdropdown").addClass("active");
        document.title = 'Create Batch - skoolyf';
      }
      else if(currentFile == "createsection")
      {
        $("#createbatch").addClass("active");
        $("#createdropdown").addClass("active");
        document.title = 'Create Section - skoolyf';
      }
      else if(currentFile == "createclub")
      {
        $("#createclub").addClass("active");
        $("#createdropdown").addClass("active");
        document.title = 'Create Club - skoolyf';
      }
      else if(currentFile == "creategroup")
      {
        $("#creategroup").addClass("active");
        $("#createdropdown").addClass("active");
        document.title = 'Create Group - skoolyf';
      }
      else if(currentFile == "updateuser")
      {
        $("#updateuser").addClass("active");
        $("#updatedropdown").addClass("active");
        document.title = 'Update User - skoolyf';
      }
      else if(currentFile == "updateschool")
      {
        $("#updateschool").addClass("active");
        $("#updatedropdown").addClass("active");
        document.title = 'Update School - skoolyf';
      }
      else if(currentFile == "updatebatch")
      {
        $("#updatebatch").addClass("active");
        $("#updatedropdown").addClass("active");
        document.title = 'Update Batch - skoolyf';
      }
      else if(currentFile == "updatesection")
      {
        $("#updatebatch").addClass("active");
        $("#updatedropdown").addClass("active");
        document.title = 'Update Section - skoolyf';
      }
      else if(currentFile == "updateclub")
      {
        $("#updateclub").addClass("active");
        $("#updatedropdown").addClass("active");
        document.title = 'Update Club - skoolyf';
      }
      else if(currentFile == "updategroup")
      {
        $("#updategroup").addClass("active");
        $("#updatedropdown").addClass("active");
        document.title = 'Update Group - skoolyf';
      }

      $('#myTab a').click(function (e) 
      {
        e.preventDefault();
        $(this).tab('show');
      });
 
      function playSound(sound)
      {
        audioElement.setAttribute('src', "public/sounds/" + sound + ".wav"); $.get();
        audioElement.play();
      }

      $('.pageToOpen').mousedown(function(event) 
      {
          switch (event.which) {
              case 3:
                  lastClickedPage = $(this).text();
                  break;
          }
      });

      context.init({preventDoubleContext: false});

      context.attach('.pageToOpen', 
        [
          {header: 'MANIPULATE PAGE'},
          {text: 'Rename', action: function(e)
          {
            e.preventDefault();

            if(lastClickedPage.indexOf('.Students.php') == -1)
            {
              rename(baseName(lastClickedPage) + ".html");
            }
            else
            {
              bootbox.alert("Sorry, this file can only be ordered but cannot be modified.");
            }
          }},
          {text: 'Duplicate', action: function(e)
          {
            e.preventDefault();

            if(lastClickedPage.indexOf('.Students.php') == -1)
            {
              duplicateContext(lastClickedPage);
            }
            else
            {
              bootbox.alert("Sorry, this file can only be ordered but cannot be modified.");
            }
          }},
          {text: 'Delete', action: function(e)
          {
            e.preventDefault();

            if(lastClickedPage.indexOf('.Students.php') == -1)
            {
              deletePage(baseName(lastClickedPage) + ".html");
            }
            else
            {
              bootbox.alert("Sorry, this file can only be ordered but cannot be modified.");
            }
          }}
        ]
      );

    });

    $('.mytooltip').tooltip();

    </script>
    <script> $.ajaxSetup({ cache: false }); </script>
  </body>
</html>