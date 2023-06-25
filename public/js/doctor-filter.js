$(document).ready(function () {

    let $grid = $('.doctor-filter-grid');
    console.log($grid);

    if ($grid.length) {
        let clearButton = $('#reset');
        let textSearch = $('#textSearch');
        let selectFields = $('.select-filter');
        let errorMessage = $('#errorMessage');
        let docSearchReset = $('#docSearchReset');
        let itemSelector = '.doc-item';
        let doctorTypeHeading = $('.doctor-type-heading');
        let filterValue;
        let filters = {};
        let qsRegex;
        $grid.isotope({
            layoutMode: 'fitRows',
            itemSelector: itemSelector,
            percentPosition: true,
            transitionDuration: '0.4s',
            filter: function () {
                let $this = $(this);
                let searchResult = qsRegex ? $this.text().match(qsRegex) : true;
                let buttonResult = filterValue ? $this.is(filterValue) : true;
                return searchResult && buttonResult;
            }
        }, updateFilteredNum);

        selectFields.on('change', function (e) {
            let $select = $(e.target);
            let filterGroup = $select.data('group');
            filters[filterGroup] = e.target.value;
            filterValue = concatValues(filters);
            $grid.isotope();
            updateFilteredNum();
        });

        textSearch.keyup(debounce(function (e) {
            if (e.keyCode === 27) {
                $(this).val('').focus();
            }
            if (textSearch.val().length > 1) {
                qsRegex = new RegExp(textSearch.val(), 'gi');
                $grid.isotope();
                updateFilteredNum();
                docSearchReset.show();
            } else {
                qsRegex = new RegExp('.*');
                $grid.isotope();
                updateFilteredNum();
                docSearchReset.hide();
            }
        }, 200));


        clearButton.on('click', function (e) {
            doctorTypeHeading.show();
            filters = {};
            filterValue = '';
            $grid.isotope();
            updateFilteredNum();
            selectFields.val('*');
            textSearch.val('');
            errorMessage.text('');
            qsRegex = new RegExp('.*');
            docSearchReset.hide();
        });

        docSearchReset.on('click', function (e) {
            textSearch.val('');
            qsRegex = new RegExp('.*');
            $grid.isotope();
            updateFilteredNum();
            docSearchReset.hide();
        });

        function concatValues(obj) {
            let value = '';
            for (let prop in obj) {
                value += obj[prop];
            }
            return value;
        }

        function debounce(fn, threshold) {
            let timeout;
            threshold = threshold || 150;
            return function debounced() {
                clearTimeout(timeout);
                let args = arguments;
                let _this = this;

                function delayed() {
                    fn.apply(_this, args);
                }

                timeout = setTimeout(delayed, threshold);
            };
        }

        /*
        * Originally used as a callback to arrangeComplete event but it was very slow to fire.
        * It is now called directly after the isotope filter function so that it fires immediately and not after the animation
        * PITA but is what it is
        * */
        function updateFilteredNum() {
            let elemNum = 0;
            $grid.each(function () {
                let el = $(this).data('isotope');
                let elems = el.filteredItems.length;
                if (!$('.doctor-cols').length && $(this).prev('h2').length) { // only run on row layout and if headings exist
                    if (elems === 0) {
                        $(this).prev('h2').hide();
                    } else {
                        $(this).prev('h2').show()
                    }
                }

                elemNum = elemNum + elems;
            });

            if (elemNum === 0) {
                errorMessage.text('No matches');
            } else {
                errorMessage.text('Found: ' + elemNum);
            }
        }
    }
});