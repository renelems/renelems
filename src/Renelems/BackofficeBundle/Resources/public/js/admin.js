var collectionHolder;

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
        	elem.parent().css("opacity", "0.5");
        },
        error:function(d)
        {
        	elem.parent().css("opacity", "1");
        },
        success:function(d)
        {
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
        	elem.parent().css("opacity", "0.5");
        },
        error:function(d)
        {
        	elem.parent().css("opacity", "1");
        },
        success:function(d)
        {
        	elem.parent().remove();
        }
    });
}