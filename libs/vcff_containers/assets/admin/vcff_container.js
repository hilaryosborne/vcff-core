var vcff_standard_container_editor;

(function ($) {

	$(document).ready(function () {

        var Shortcodes = vc.shortcodes;
        
        window.VCFFStandardContainerView = vc.shortcode_view.extend( {
            events: {
                'click > .vc_controls [data-vc-control="delete"]': 'deleteShortcode',
                'click > .vc_controls [data-vc-control="add"]': 'addElement',
                'click > .vc_controls [data-vc-control="edit"]': 'editElement',
                'click > .vc_controls [data-vc-control="clone"]': 'clone',
                'click > .wpb_element_wrapper > .vc_empty-container': 'addToEmpty'
            },
            initialize: function ( options ) {
                window.VCFFStandardContainerView.__super__.initialize.call( this, options );
                _.bindAll( this, 'setDropable', 'dropButton' );
            },
            ready: function ( e ) { 
                window.VCFFStandardContainerView.__super__.ready.call( this, e );
                this.setDropable();
                return this;
            },
            render: function () { 
                this.$el.find('.cntr-lbl strong').html(this.model.get('params').label);
                window.VCFFStandardContainerView.__super__.render.call( this );
                this.setEmpty();
                return this;
            },
            changeShortcodeParams: function ( model ) { 
                this.$el.find('.cntr-lbl strong').html(this.model.get('params').label);
                window.VCFFStandardContainerView.__super__.changeShortcodeParams.call( this, model );
            },
            addToEmpty: function ( e ) {
                e.preventDefault();
                if ( $( e.target ).hasClass( 'vc_empty-container' ) ) {
                    this.addElement( e );
                }
            },
            setDropable: function () { console.log(this.model.get( 'shortcode' ));
                this.$content.droppable( {
                    greedy: true,
                    accept: ".dropable_el,.dropable_row",
                    hoverClass: "wpb_ui-state-active",
                    drop: this.dropButton
                } );
                return this;
            },
            dropButton: function ( event, ui ) {
                if ( ui.draggable.is( '#wpb-add-new-element' ) ) {
                    new vc.element_block_view( { model: { position_to_add: 'end' } } ).show( this );
                } else if ( ui.draggable.is( '#wpb-add-new-row' ) ) {
                    this.createRow();
                }
            },
            setEmpty: function () {
                this.$content.addClass( 'vc_empty-container' );
            },
            unsetEmpty: function () {
                this.$content.removeClass( 'vc_empty-container' );
            },
            checkIsEmpty: function () {
                if ( Shortcodes.where( { parent_id: this.model.id } ).length ) {
                    this.unsetEmpty();
                } else {
                    this.setEmpty();
                }
                window.VCFFStandardContainerView.__super__.checkIsEmpty.call( this );
            },
            createRow: function () {
                var row = Shortcodes.create( { shortcode: 'vc_row_inner', parent_id: this.model.id } );
                Shortcodes.create( { shortcode: 'vc_column_inner', params: { width: '1/1' }, parent_id: row.id } );
                return row;
            },
            deleteShortcode: function ( e ) {
                var parent_id = this.model.get( 'parent_id' ),
                    parent;
                if ( _.isObject( e ) ) {
                    e.preventDefault();
                }
                var answer = confirm( window.i18nLocale.press_ok_to_delete_section );
                if ( answer !== true ) {
                    return false;
                }
                this.model.destroy();
                if ( parent_id && ! vc.shortcodes.where( { parent_id: parent_id } ).length ) {
                    parent = vc.shortcodes.get( parent_id );
                    if ( ! _.contains( [
                        'vc_column',
                        'vc_column_inner'
                    ], parent.get( 'shortcode' ) ) ) {
                        parent.destroy();
                    }
                } else if ( parent_id ) {
                    parent = vc.shortcodes.get( parent_id );
                    if ( parent && parent.view && parent.view.setActiveLayoutButton ) {
                        parent.view.setActiveLayoutButton();
                    }
                }
            }
        } );
        
	});

})(window.jQuery);