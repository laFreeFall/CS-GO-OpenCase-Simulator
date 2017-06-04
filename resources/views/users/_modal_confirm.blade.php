<div class="modal fade" id="modal-confirm" role="dialog" data-itemid="0">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">Confirmation</h4>
            </div>
            <div class="modal-body" id="modal-confirm-modal-body">
                <img src="" alt="" id="modal-confirm-item-image" class="modal-confirm-item-image center-block">
                <p class="text-center" id="modal-confirm-text">Do you really want to {{ $action }} <br>
                    @unless($action == 'delete')
                        <strong><span id="modal-confirm-item-name"></span></strong><br> for
                        <strong><span id="modal-confirm-item-price"></span></strong><span class="fa fa-diamond"></span>?
                    @endunless
                </p>
            </div>
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-success pull-left confirm-delete-buttons"
                        id="btn-confirm-sell-inventory-item"
                        data-dismiss="modal"
                >
                    <span class="fa fa-check-circle-o"></span>
                    <span class="confirm-delete-text">Yes</span>
                </button>
                <button type="button"
                        class="btn btn-danger pull-right confirm-delete-buttons"
                        id="btn-cancel-sell-inventory-item"
                        data-dismiss="modal"
                >
                    <span class="fa fa-times-circle-o"></span>
                    <span class="confirm-delete-text">No</span>
                </button>
            </div>
        </div>
    </div>
</div>
