/*!
 * selectNodeContent modal attachment, require Twitter Bootstrap modal plugin
 */
;(function ( $, window, document, undefined ) {

    // Create the defaults once
    var pluginName = 'selectNodeContent',
        defaults = {
            //something default?
        };

    // The actual plugin constructor
    function Plugin( element, options ) {
        this.element = element;

        this.options = $.extend( {}, defaults, options) ;

        this._defaults    = defaults;
        this._name        = pluginName;

        this.modal        = null;
        this.redirect     = true;

        this.init();              //initialize
    }

    // Plugin constructor
    Plugin.prototype.init = function () {

        // console.log($(this.element));
        if (!this.isModalExists()) {
            this.createModal();
        };

        //assign confirmation modal
        this.attachModal();
    };

    Plugin.prototype.createModal = function () {
        var element = $('<div id="modalForm" class="modal hide" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Edit</h3></div><div class="modal-body"></div><div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button></div></div>');

        $('body').append(element);

    };

    Plugin.prototype.isModalExists = function () {

        return $('#modalForm').length;
    };

    //assign change events
    Plugin.prototype.attachModal = function () {
        var self = this;
        this.modal = $('#modalForm');
        $(this.element).click(function(ev) {
            var href  = $(this).attr('href');

            //call ajax
            $.get(href, function(data) {

                self.modal.find('.modal-body').html(data.content);

                //
                self.modal.removeData('modal').modal({
                    show:true
                });

                //
                self.attachBundleSelector();

            }, 'json');

            return false;
        });
    };

    Plugin.prototype.attachBundleSelector = function() {
        var self = this;
        this.modal.find('a.select-bundle').on('click', function() {

            console.log($(this).attr('href'));

            var href  = $(this).attr('href');

            //call ajax
            $.get(href, function(data) {
                console.log(data);
                self.modal.find('.modal-body').html(data.content);

                self.attachFormSubmit();
            }, 'json');


            return false;
        });
    };

    Plugin.prototype.attachFormSubmit = function () {
        self = this;
        this.modal.find('.modal-body').on('submit', 'form', function() {
            var form = $(this);
            var querystring = form.serialize();
            $.post(form.attr('action'), querystring, function(data) {
                if (data.verdict == 'success') {
                    // console.log('OK');
                    //close modal
                    self.modal.modal('hide');
                } else {
                    // console.log('FAIL');
                    //error - replace the form with populated errors
                }
                form.parent().empty().append(data.content);
            }, 'json');

            return false;
        });
    };


    // A plugin wrapper around the constructor,
    $.fn[pluginName] = function ( options ) {
        //check dependency
        if (typeof $.fn['modal'] === 'function') {
            return this.each(function () {
                if (!$.data(this, 'plugin_' + pluginName)) {
                    $.data(this, 'plugin_' + pluginName,
                    new Plugin( this, options ));
                }
            });
        } else {
            throw 'Modal extension is undefined';
        }
    };

})( jQuery, window, document );
