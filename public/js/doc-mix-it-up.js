
// To keep our code clean and modular, all custom functionality will be contained inside a single object literal called "dropdownFilter".


/* Doctor Module */
var dropdownFilter = {

    // Declare any variables we will need as properties of the object

    $filters: null,
    $reset: null,
    groups: [],
    outputArray: [],
    outputString: '',

    // The "init" method will run on document ready and cache any jQuery objects we will need.

    init: function(){
        var self = this; // As a best practice, in each method we will assign "this" to the variable "self" so that it remains scope-agnostic.
        // We will use it to refer to the parent "dropdownFilter" object so that we can share methods and properties between all parts of the object.

        self.$filters = $('#Filters');
        self.$reset = $('#Reset');
        self.$container = $('#Container');

        self.$filters.find('fieldset').each(function(){
            self.groups.push({
                $dropdown: $(this).find('select'),
                active: ''
            });
        });

        self.bindHandlers();
    },

    // The "bindHandlers" method will listen for whenever a select is changed.

    bindHandlers: function(){
        var self = this;

        // Handle select change

        self.$filters.on('change', 'select', function(e){
            e.preventDefault();
            $('#errorMessage').html(' ');

            self.parseFilters();
        });

        // Handle reset click

        self.$reset.on('click', function(e){
            e.preventDefault();
            $('#errorMessage').html(' ');

            self.$filters.find('select').val('');

            self.parseFilters();
        });
    },

    // The parseFilters method pulls the value of each active select option

    parseFilters: function(){
        var self = this;

        // loop through each filter group and grap the value from each one.

        for(var i = 0, group; group = self.groups[i]; i++){
            group.active = group.$dropdown.val();
        }

        self.concatenate();
    },

    // The "concatenate" method will crawl through each group, concatenating filters as desired:

    concatenate: function(){
        var self = this;

        self.outputString = ''; // Reset output string

        for(var i = 0, group; group = self.groups[i]; i++){
            self.outputString += group.active;
        }

        // If the output string is empty, show all rather than none:

        !self.outputString.length && (self.outputString = 'all');

        //console.log(self.outputString);

        // ^ we can check the console here to take a look at the filter string that is produced

        // Send the output string to MixItUp via the 'filter' method:

        if(self.$container.mixItUp('isLoaded')){
            self.$container.mixItUp('filter', self.outputString);
        }
    }
};

// On document ready, initialise our code.
let $ = jQuery;
$(function(){

    // Initialize dropdownFilter code

    dropdownFilter.init();

    // Instantiate MixItUp

    $('#Container').mixItUp({
        controls: {
            enable: true // we won't be needing these
        },
        layout: {display: "flex", containerClass: "", containerClassFail: "fail"},
        callbacks: {
            onMixFail: function(){
                $('#errorMessage').html('No related matches');
            }
        }
    });
});