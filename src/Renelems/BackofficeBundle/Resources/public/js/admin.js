var collectionHolder;
var tag_ids = new Array();
var search = '';

$(document).ready(function() {
	// Get the ul that holds the collection of tags
	collectionHolder = $('div.images');
	
	// count the current form inputs we have (e.g. 2), use that as the new
	// index when inserting a new item (e.g. 2)
	collectionHolder.data('index', collectionHolder.find(':input').length);

	var prototype = collectionHolder.data('prototype');
	// get the new index
    var index = collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace('renelems_dbbundle_project[images][__name__][file]', 'renelems_dbbundle_project[images][__name__][file][]');
    newForm = newForm.replace(/__name__/g, index);

    $("#addImage").prepend(newForm);
});

function init_autocomplete() {
	$( ".tag_autocomplete" ).autocomplete({
		source: function(request, callback)
        {
			search = request.term;
            $.getJSON(urlApiAutoComplete, {search: search, object: 'tag'}, callback);
        },
		minLength: 1,
		search: function( event, ui ) {
			$('#autocomplete_loader').show();
			$('.add_tag_button').hide();
		},
		response: function( event, ui ) {
			$('#autocomplete_loader').hide();
			$('.add_tag_button').attr('onclick', 'addTag(\''+search+'\')')
			$('.add_tag_button').show();
			
		},
		select: function( event, ui ) {
			$('.add_tag_button').hide();
			if(ui.item)
            {
                if(tag_ids.indexOf(parseInt(ui.item.value))==-1)
                {
                    $('#autocomplete_list').append('<li data-tag-id="'+ui.item.value+'"><i class="fa fa-tag"></i>'+ui.item.label+'&nbsp;<i class="fa fa-times delete_temp_tag" alt="Verwijder" /></li>');
                    tag_ids.push(ui.item.value);
                    init_autocomplete_actions('tag');
                    $('input[name="autocomplete_ids"]').val(tag_ids);
                }
                else
                {
                    alert("Label is al gekoppeld!");
                }
            }
			setTimeout(function (){
				$( ".tag_autocomplete" ).val('');
			}, 100);
		},
		close: function( event, ui ) {
			if($(this).val() == '')
				$('.add_tag_button').hide();
		}
    });
}

function init_autocomplete_actions(object)
{
	if(object == 'tag') {
	    if(tag_ids.length == 0)
	    {
	        $('#autocomplete_list li').each(function()
	        {
	            tag_ids.push(parseInt($(this).attr('data-tag-id')));
	        })
	    }
	    
	    // Function for deleting TEMPORARY linked artists
	    $('.delete_temp_tag').click(function()
	    {
	        var tag_id = $(this).parent().attr('data-tag-id');
	        // remove LI from DOM
	        $(this).parent().remove();
	        // find and remove artist ID from array
	        var tag_index = tag_ids.indexOf(parseInt(tag_id));
	        if(tag_index != -1)
	        {
	            tag_ids.splice(tag_index, 1);
	        }
	    });    
	    // Function for deleting already linked artists
	    $('.delete_tag').click(function()
	    {
	        var tag_id = $(this).parent().attr('data-tag-id');
	        var project_id = $(this).parent().attr('data-project-id');
	        // remove LI from DOM
	        $(this).parent().remove();
	        // find and remove artist ID from array
	        var tag_index = tag_ids.indexOf(parseInt(tag_id));
	        if(tag_index != -1)
	        {
	            tag_ids.splice(tag_index, 1);
	        }
	        $('input[name="autocomplete_ids"]').val(tag_ids);
	    });
	    $('input[name="autocomplete_ids"]').val(tag_ids);
	}
}

function updateImagePosition(elem, newPosition, oldPosition) {
	var elemClass = elem.attr('class').split("_");
	$.ajax({
        url: urlApiUpdateImagePosition,
        dataType: 'json',
        type: 'POST',
        data:
        {
            object: elemClass[0],
            id: elemClass[1],
            oldPosition: oldPosition,
            newPosition: newPosition,
        },
        beforeSend:function(d)
        {
        	$('#loader').show();
        	elem.parent().css("opacity", "0.5");
        },
        error:function(d)
        {
        	$('#loader').hide();
        	elem.parent().css("opacity", "1");
        },
        success:function(d)
        {
        	$('#loader').hide();
        	elem.parent().css("opacity", "1");
        }
    });
}

function removeImage(elem) {
	var elem = $(elem);
	var id = elem.data('id');
	var object = elem.data('object'); 
	$.ajax({
        url: urlApiRemoveImage,
        dataType: 'json',
        type: 'POST',
        data:
        {
            object: object,
            id: id,
        },
        beforeSend:function(d)
        {
        	$('#loader').show();
        	elem.parent().css("opacity", "0.5");
        },
        error:function(d)
        {
        	$('#loader').hide();
        	elem.parent().css("opacity", "1");
        },
        success:function(d)
        {
        	$('#loader').hide();
        	elem.parent().remove();
        }
    });
}

function toggleActive(elem) {
	var elem = $(elem);
	var id = elem.data('id');
	var object = elem.data('object'); 
	$.ajax({
        url: urlApiToggleActive,
        dataType: 'json',
        type: 'POST',
        data:
        {
            object: object,
            id: id,
        },
        beforeSend:function(d)
        {
        	$('#loader').show();
        },
        error:function(d)
        {
        	$('#loader').hide();
        },
        success:function(d)
        {
        	$('#loader').hide();
        	if(d.active == true)
        		elem.html('<i class="fa fa-check"></i>');
        	else
        		elem.html('<i class="fa fa-times"></i>');
        }
    });
}


function addTag(title)
{
	$('.add_tag_button').remove();
	$.ajax({
        url: urlApiAddTag,
        dataType: 'json',
        type: 'POST',
        data:
        {
            title: title
        },
        beforeSend:function(d)
        {
        	$('#loader').show();
        },
        error:function(d)
        {
        	$('#loader').hide();
        },
        success:function(d)
        {
        	$('#loader').hide();
        	if(tag_ids.indexOf(parseInt(d.id))==-1) {
        		$('#autocomplete_list').append('<li data-tag-id="'+d.id+'"><i class="fa fa-tag"></i>'+d.title+'&nbsp;<i class="fa fa-times delete_temp_tag" alt="Verwijder" /></li>');
        		tag_ids.push(d.id);
        		init_autocomplete_actions('tag');
        		$('input[name="autocomplete_ids"]').val(tag_ids);
            } else {
            	alert("Label is al gekoppeld!");
        	}
        }
    });
}