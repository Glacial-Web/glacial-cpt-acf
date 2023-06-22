$(document).ready(function () {
    $(document).keyup(function (e) {
        if (e.keyCode === 27) {
            let textSearch = $('#textSearch');
            if (textSearch.is(':focus')) {
                textSearch.val('');
                textSearch.focus();
            }
        }
    });

    let $grid = $('.doctor-filter-grid')

    if ($grid.length) {
        let clearButton = $('#reset');
        let textSearch = $('#textSearch');
        let selectFields = $('.select-filter');
        let errorMessage = $('#errorMessage');
        let docSearchReset = $('#docSearchReset');
        let itemSelector = '.doc-item';
        let filterValue;
        let filters = {};
        let qsRegex;
        $grid.isotope({
            layoutMode: 'fitRows',
            itemSelector: itemSelector,
            percentPosition: true,
            filter: function () {
                let $this = $(this);
                let searchResult = qsRegex ? $this.text().match(qsRegex) : true;
                let buttonResult = filterValue ? $this.is(filterValue) : true;
                return searchResult && buttonResult;
            }
        });

        selectFields.on('change', function (e) {
            let $select = $(e.target);
            let filterGroup = $select.data('group');
            filters[filterGroup] = e.target.value;
            filterValue = concatValues(filters);
            $grid.isotope();
            hideShowError();
        });

        textSearch.keyup(debounce(function () {
            if (textSearch.val().length > 1) {
                qsRegex = new RegExp(textSearch.val(), 'gi');
                $grid.isotope();
                docSearchReset.show();
                hideShowError();
            } else {
                qsRegex = new RegExp('.*');
                $grid.isotope();
                docSearchReset.hide();
                hideShowError();

            }
        }, 200));

        function concatValues(obj) {
            let value = '';
            for (let prop in obj) {
                value += obj[prop];
            }
            // console.log(value)
            return value;
        }

        function hideShowError() {

            setTimeout(function () {
                let elems = 0;
                $('.doc-grid').each(function () {
                    let el = $(this).data('isotope');
                    elems = elems + el.filteredItems.length;
                });

              /*  let headings = $('.filters-on h2');

                headings.each(function () {
                    let div = $(this).next('div');
                    let docs = $(div).data('isotope');
                    if (docs.filteredItems.length === 0) {
                        $(this).hide()
                        $(div).hide()
                    } else {
                        $(this).show();
                        $(div).show();
                    }

                });*/

                if (elems === 0) {
                    errorMessage.text('No Doctors found');
                } else {
                    errorMessage.text('Doctors found: ' + elems);
                }
            }, 200);
        }

        clearButton.on('click', function (e) {
            selectFields.val('*');
            textSearch.val('');
            errorMessage.text('');
            qsRegex = new RegExp('.*');
            filters = {};
            filterValue = '';
            $grid.isotope();
        });

        docSearchReset.on('click', function (e) {
            textSearch.val('');
            qsRegex = new RegExp('.*');
            $grid.isotope();
            docSearchReset.hide();
            hideShowError();
        });

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

    }
});