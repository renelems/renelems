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

    $(".imageList").prepend(newForm);
	
	// add a delete link to all of the existing tag form li elements
	//$collectionHolder.find('table').each(function() {
    //    addImageFormDeleteLink($(this));
    //});
});

function addImageFormDeleteLink($imageFormLi) {
    var $removeFormA = $('<a href="#" class="btn delete pull-right"><i class="fa fa-trash-o"></i> delete this image</a>');
    $imageFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        if(confirm("weet je zeker dat je deze afbeelding wilt verwijderen?")) {
	        // remove the li for the tag form
	        $imageFormLi.remove();
        }
    });
}