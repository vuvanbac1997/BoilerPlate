$(function () {
    $('.button-checkbox').each(function () {

        // Settings
        var $widget = $(this),
            $button = $widget.find('button'),
            $checkbox = $widget.find('input:checkbox'),
            color = $button.data('color'),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };

        // Event Handlers
        $button.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $button.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$button.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $button
                    .removeClass('btn-default')
                    .addClass('btn-' + color + ' active');
            }
            else {
                $button
                    .removeClass('btn-' + color + ' active')
                    .addClass('btn-default');
            }
        }

        // Initialization
        function init() {

            updateDisplay();

            // Inject the icon if applicable
            if ($button.find('.state-icon').length == 0) {
                $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
            }
        }
        init();
    });
});

/**
 * Use guide

 <div class="container">
 <h3>jQuery Checkbox Buttons<br />
 <small>Buttons that change the state of their own hidden checkboxes, and vice-versa!</small>
 </h3>
 <br />

 <span class="button-checkbox">
 <button type="button" class="btn" data-color="primary">Unchecked</button>
 <input type="checkbox" class="hidden" />
 </span>
 <span class="button-checkbox">
 <button type="button" class="btn" data-color="primary">Checked</button>
 <input type="checkbox" class="hidden" checked />
 </span>

 <hr />

 <!-- All colors -->
 <span class="button-checkbox">
 <button type="button" class="btn" data-color="default">Default</button>
 <input type="checkbox" class="hidden" checked />
 </span>
 <span class="button-checkbox">
 <button type="button" class="btn" data-color="primary">Primary</button>
 <input type="checkbox" class="hidden" checked />
 </span>
 <span class="button-checkbox">
 <button type="button" class="btn" data-color="success">Success</button>
 <input type="checkbox" class="hidden" checked />
 </span>
 <span class="button-checkbox">
 <button type="button" class="btn" data-color="info">Info</button>
 <input type="checkbox" class="hidden" checked />
 </span>
 <span class="button-checkbox">
 <button type="button" class="btn" data-color="warning">Warning</button>
 <input type="checkbox" class="hidden" checked />
 </span>
 <span class="button-checkbox">
 <button type="button" class="btn" data-color="danger">Danger</button>
 <input type="checkbox" class="hidden" checked />
 </span>
 <span class="button-checkbox">
 <button type="button" class="btn" data-color="link">Link</button>
 <input type="checkbox" class="hidden" checked />
 </span>

 <hr />

 <!-- All sizes -->
 <span class="button-checkbox">
 <button type="button" class="btn btn-xs" data-color="primary">Primary</button>
 <input type="checkbox" class="hidden" checked />
 </span>
 <span class="button-checkbox">
 <button type="button" class="btn btn-sm" data-color="primary">Primary</button>
 <input type="checkbox" class="hidden" checked />
 </span>
 <span class="button-checkbox">
 <button type="button" class="btn" data-color="primary">Primary</button>
 <input type="checkbox" class="hidden" checked />
 </span>
 <span class="button-checkbox">
 <button type="button" class="btn btn-lg" data-color="primary">Primary</button>
 <input type="checkbox" class="hidden" checked />
 </span>

 <hr />

 <!-- Icons -->
 <span class="button-checkbox">
 <button type="button" class="btn" data-color="primary"><i class="glyphicon glyphicon-envelope"></i></button>
 <input type="checkbox" class="hidden" checked />
 </span>
 <span class="button-checkbox">
 <button type="button" class="btn" data-color="primary"><i class="glyphicon glyphicon-phone"></i></button>
 <input type="checkbox" class="hidden" />
 </span>
 </div>
* */