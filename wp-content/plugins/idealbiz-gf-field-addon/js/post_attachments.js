(function($){

  // Dropzone related methods.

  /**
   * Maybe enable the Dropzone element and change the maxfiles option.
   *
   * [Object] dropzone The Dropzone object.
   */
  function maybeEnableDropzone(dropzone) {
    var $element = $(dropzone.element);
    var $gallery = $element.siblings('.attachment-gallery').first();
    var $items = $gallery.children('.item');
    var maxFiles = $element.data('files');
    var countDiff = maxFiles - $items.length;

    if (countDiff > 0) {
      dropzone.options.maxFiles = countDiff;
    }

    // Limit disable method calls.
    if (countDiff === 1) {
      dropzone.enable();
    }
  }

  /**
   * Handle successful uploads.
   *
   * [Object] file     The uploaded file.
   * [Object] response The server's response.
   */
  function handleSuccess(file, response) {
    var $element = $(this.element);
    var $input = $('#' + $element.data('input-id'));
    var imageIds = [];
    var currentIds = $input.val();
    var $count = $input.siblings('.attachment-count').first();
    var $gallery = $input.siblings('.attachment-gallery').first();
    var $newItem = $('<div class="item">' + response.data.image_markup + '</div>');
    var maxFiles = $element.data('files');

    // Check if the current values are empty and push values if not.
    if (currentIds.length > 0) {
      imageIds.push(currentIds);
    }

    // Add new image to list.
    imageIds.push(response.data.id);

    // Update value string.
    $input.val(imageIds.join());

    // Add item to gallery and delete event.
    $('<span/>', { 'class': 'item-delete' }).on(
        'click', { dropzone: this }, deleteGalleryItem
    ).appendTo($('<div/>', {
      'class': 'item',
      html: response.data.image_markup
    }).appendTo($gallery));

    // Remove file from dropzone.
    this.removeFile(file);

    var $items = $gallery.children('.item');

    // Maybe disable upload.
    if ($items.length === maxFiles) {
      this.disable();
    }

    // Update count.
    $count.find('.current-files').text($items.length);
    this.options.maxFiles = maxFiles - $items.length;
  };

  /**
   * Setup and initialize Dropzones.
   */
  function setupDropzones() {
    var $dropzones = $('.gfield_dropzone');

    $dropzones.each(function() {

      // Don't load when disabled.
      if ($(this).data('disabled') === 'disabled') {
        return;
      }

      var fileSize = parseInt($(this).data('size'));
      var maxFiles = parseInt($(this).data('files'));

      if (fileSize === 0) {
        fileSize = null;
      }

      if (maxFiles === 0) {
        maxFiles = null;
      }

      $(this).dropzone({
        url: $(this).data('url'),
        acceptedFiles: $(this).data('extensions'),
        maxFilesize: fileSize,
        maxFiles: maxFiles,
        init: function() {
          var $dropzone = $(this.element)
          var $items = $dropzone.siblings('.attachment-gallery').first().children('.item');
          var maxFiles = $dropzone.data('files');

          // Add delete event.
          $items.find('.item-delete').on('click', { dropzone: this }, deleteGalleryItem );

          // Disable if the count is already on the limit, or over.
          if ($items.length >= maxFiles) {
            this.disable();
          }

          this.on('success', handleSuccess);
        }
      });
    });
  };

  // Sortable related methods.

  /**
   * Delete one of the items.
   *
   * This doesn't actually delete the created attachment, but only prevents save
   * on the meta_key.
   *
   * [Object] evt The event object.
   */
  function deleteGalleryItem(evt) {
    var $item = $(this).parent();
    var $input = $('#' + $item.parent().data('field-id'));
    var removedId = $item.find('img').data('id');
    var sort = $input.val().split(',');
    var $count = $input.siblings('.attachment-count').first();

    // Remove element.
    $item.remove();

    // Remove from sort.
    sort.splice(sort.indexOf(removedId), 1);

    $input.val(sort);

    // Update count.
    $count.find('.current-files').text(sort.length);

    // Act on Dropzone.
    maybeEnableDropzone(evt.data.dropzone);
  }

  /**
   * Update the sorting of gallery elements.
   *
   * [Object] evt The event information.
   */
  function updateGallerySort(evt) {
    var $item = $(evt.item);
    var $input = $('#' + $item.parent().data('field-id'));
    var sort = $input.val().split(',');
    var itemId = sort[evt.oldIndex];

    // Remove element from array.
    sort.splice(evt.oldIndex, 1);

    // Add element in new position.
    sort.splice(evt.newIndex, 0, itemId);

    $input.val(sort);
  };

  function setupSortables() {
    var galleries = document.getElementsByClassName('attachment-gallery');

    $.each(galleries, function(index, value) {
      var $gallery = $(value);
      var sortable = new Sortable(value, {
        group: $gallery.data('field-id'),
        sort: true,
        onSort: updateGallerySort
      });
    });
  };

  // Prevent auto discovery of dropzone.
  Dropzone.autoDiscover = false;

  $(document).ready(function() {
    setupDropzones();
    setupSortables();
  });
})(jQuery);
