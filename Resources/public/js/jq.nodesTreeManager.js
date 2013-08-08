/*!
 * Confirmation modal attachment, require Twitter Bootstrap modal plugin
 */
;(function ( $, window, document, undefined ) {

    // Create the defaults once
    var pluginName = 'nodesTreeManager',
        defaults = {
            //something default?
        };

    // The actual plugin constructor
    function Plugin( element, options ) {
        this.element   = element;

        this.options   = $.extend( {}, defaults, options) ;

        this._defaults = defaults;
        this._name     = pluginName;
        this.modal     = null;
        this.redirect  = true;

        this.init();              //initialize
    }

    // Plugin constructor
    Plugin.prototype.init = function () {

        // console.log($(this.element));
        if (!this.isModalExists()) {
            this.modal = this.createModal();
        };

        //create or edit node
        this.attachManageModal();

        //attach content selector modal
        this.attachContentSelector();

        //attach submit handler for internal form
        this.attachFormSubmit();


        console.log('attach assign content modal');
    };

    Plugin.prototype.createModal = function () {
        var element = $('<div id="modalForm" class="modal hide" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true" style="width: 600px;"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Edit</h3></div><div class="modal-body"></div><div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button></div></div>');

        $('body').append(element);

        return element;
    };

    Plugin.prototype.isModalExists = function () {

        return $('#modalForm').length;
    };

    Plugin.prototype.attachContentSelector = function() {
        var self = this;

        $(this.element).on('click', 'a[data-manage-content]', function() {
            var href  = $(this).data('manage-content');
            var title = $(this).data('modal-title');

            $.get(href, function(data) {
                //add modal title if any
                if (title) { self.modal.find('#dataConfirmLabel').html(title); };
                //add content
                self.modal.find('.modal-body').html(data.content);
                self.modal.removeData('modal').modal({show: true});

                //attach provider selector
                self.attachBundleSelector();
            }, 'json');

            return false;
        });
    };

    Plugin.prototype.attachBundleSelector = function() {
        var self = this;
        this.modal.find('a.select-bundle').on('click', function() {
            var href  = $(this).attr('href');
            //call ajax
            $.get(href, function(data) {
                self.modal.find('.modal-body').html(data.content);
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
                    if (self.redirect) {
                        window.location.reload();
                    }
                    //close modal
                    self.modal.modal('hide');
                } else {
                    // console.log('FAIL');
                    //error - replace the form with populated errors
                }
                form.parent().empty().append(data.content);
                $('#btn_nodesbundle_nodetype_slug').slugify('#btn_nodesbundle_nodetype_title');
            }, 'json');

            return false;
        });
    };

    Plugin.prototype.attachManageModal = function() {
        var self = this;

        $(this.element).on('click', 'a[data-manage-node]', function() {
            var href  = $(this).data('manage-node');
            var title = $(this).data('modal-title');

            $.get(href, function(data) {
                //add modal title if any
                if (title) { self.modal.find('#dataConfirmLabel').html(title); };
                //add content
                self.modal.find('.modal-body').html(data.content);
                self.modal.removeData('modal').modal({show: true});
                //add slugify
                $('#btn_nodesbundle_nodetype_slug').slugify('#btn_nodesbundle_nodetype_title');
            }, 'json');

            return false;
        });
    }

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
