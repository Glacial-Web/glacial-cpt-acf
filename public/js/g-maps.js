/*
* Map Styles
* */
const mapStyles = [
    {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#e9e9e9"
            },
            {
                "lightness": 17
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#f5f5f5"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 17
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 29
            },
            {
                "weight": 0.2
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 18
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#f5f5f5"
            },
            {
                "lightness": 21
            }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#dedede"
            },
            {
                "lightness": 21
            }
        ]
    },
    {
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#ffffff"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "saturation": 36
            },
            {
                "color": "#333333"
            },
            {
                "lightness": 40
            }
        ]
    },
    {
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#f2f2f2"
            },
            {
                "lightness": 19
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#fefefe"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#fefefe"
            },
            {
                "lightness": 17
            },
            {
                "weight": 1.2
            }
        ]
    }
]

// change this to the max distance you want to show, will alert if no practices are found within this distance
const maxDistance = 200;

//Get the icon color from the root styles
const rootStyles = getComputedStyle(document.documentElement);
const iconColor = rootStyles.getPropertyValue('--cpt-icon-color').trim();

const endpoint = window.gmaps.site_url + '/wp-json/glacial/v1/locations';
const openInfoLink = document.getElementsByClassName('open-info-window');
const activeClass = 'is-open';

/*
* Kick this off. Function called by the Maps api script using the callback parameter.
* */
function initMap() {
    const locationSearch = document.getElementById('locationSearch');
    const mapId = document.getElementById('glacialMap');

    if (mapId !== null) {
        const map = new google.maps.Map(mapId, {
            zoom: 12,
            styles: mapStyles
        });

        fetch(endpoint)
            .then(response => response.json())
            .then(data => {
                map.data.addGeoJson(data, {idPropertyName: 'locationid'});

                /*
                * Center map given the markers
                * */
                const bounds = new google.maps.LatLngBounds();
                map.data.forEach((practice) => {
                    const loc = practice.getGeometry().get();
                    bounds.extend(loc);
                });

                map.fitBounds(bounds, 110);

                map.data.setStyle(() => {
                    return {
                        icon: {
                            path: "M25,1c-8.82031,0 -16,7.17969 -16,16c0,14.11328 14.62891,30.94531 15.25,31.65625c0.19141,0.21875 0.46094,0.34375 0.75,0.34375c0.30859,-0.01953 0.55859,-0.125 0.75,-0.34375c0.62109,-0.72266 15.25,-17.84375 15.25,-31.65625c0,-8.82031 -7.17969,-16 -16,-16zM25,12c3.3125,0 6,2.6875 6,6c0,3.3125 -2.6875,6 -6,6c-3.3125,0 -6,-2.6875 -6,-6c0,-3.3125 2.6875,-6 6,-6z",
                            fillColor: iconColor,
                            fillOpacity: 1,
                            strokeWeight: 0,
                            scale: 1.2,
                            anchor: new google.maps.Point(24, 48)
                        },
                    };
                });

                const infoWindow = new google.maps.InfoWindow();

                /*
                * Build the info window content when the user clicks on a location.
                * */
                map.data.addListener('click', (event) => {
                    const name = event.feature.getProperty('name');
                    const address = event.feature.getProperty('address');
                    const phone = event.feature.getProperty('phone');
                    const phoneHref = 'tel:' + phone.replace(/\D/g, '');
                    const link = event.feature.getProperty('link');
                    const position = event.feature.getGeometry().get();
                    const directions = `https://www.google.com/maps/dir/?api=1&destination=${position.lat()},${position.lng()}`;
                    const phone_text = phone ? `<p>${phone}</p>` : '';

                    const content =
                        `<div class="infowindow">
                          <h2>${name}</h2>
                          <div class="infowindow-content">
                          <p>${address}</p>
                          <p><a href="${phoneHref}"> ${phone_text}</a></p>
                            <p><a href="${link}">More Info</a></p>
                            <p><a href="${directions}" target="_blank">Get Directions</a>
                          </div>
                        </div>`;

                    infoWindow.setContent(content);
                    infoWindow.setPosition(position);
                    infoWindow.setOptions({pixelOffset: new google.maps.Size(0, -30)});
                    infoWindow.open(map);

                    // find the link with the locationid and add active class
                    for (let i = 0; i < openInfoLink.length; i++) {
                        if (openInfoLink[i].getAttribute('data-locationid') == event.feature.getProperty('locationid')) {
                            openInfoLink[i].classList.add(activeClass);
                        }
                    }
                });

                document.addEventListener('click', function (e) {
                    if (e.target.classList.contains('location-title')) {
                        e.preventDefault();

                        for (let j = 0; j < openInfoLink.length; j++) {
                            openInfoLink[j].classList.remove(activeClass);
                        }

                        e.target.classList.add(activeClass);

                        const locationid = e.target.parentElement.getAttribute('data-locationid');
                        map.data.forEach((practice) => {
                            if (practice.getProperty('locationid') == locationid) {
                                google.maps.event.trigger(map.data, 'click', {
                                    feature: practice,
                                });
                            }
                        });
                    }
                });

                map.addListener('click', () => {
                    infoWindow.close();
                });

                infoWindow.addListener('close', () => {
                    for (let j = 0; j < openInfoLink.length; j++) {
                        openInfoLink[j].classList.remove(activeClass);
                    }
                });

            })
            .catch(error => console.error('Error:', error.message));

        /*
        * Create the search box and link it to the UI element.
        * */
        if (locationSearch !== null) {
            const input = document.createElement('input');
            const options = {
                fields: ["address_components", "geometry", "icon", "name"],
                componentRestrictions: {country: 'us'},
            };

            input.setAttribute('id', 'pac-input');
            input.setAttribute('type', 'text');
            input.setAttribute('placeholder', 'Enter an address');
            locationSearch.appendChild(input);

            /*
            * Make the search box into a Places Autocomplete search box and
            * select which detail fields should be returned.
            * */
            const autocomplete = new google.maps.places.Autocomplete(input, options);

            /*
            * Set the origin point when the user selects an address
            * */
            const originMarker = new google.maps.Marker({map: map});
            originMarker.setVisible(false);
            let originLocation = map.getCenter();

            autocomplete.addListener('place_changed', async () => {
                const place = autocomplete.getPlace();

                if (!place.geometry) {
                    // User entered the name of a Place that was not suggested and
                    // pressed the Enter key, or the Place Details request failed.
                    window.alert('No address available for input: \'' + place.name + '\'');
                    return;
                }

                /*
                * Recenter the map to the selected address
                * */
                originLocation = place.geometry.location;
                originMarker.setPosition(originLocation);
                originMarker.setVisible(true);

                const bounds = new google.maps.LatLngBounds();
                bounds.extend(originLocation);
                map.data.forEach((practice) => {
                    const loc = practice.getGeometry().get();
                    bounds.extend(loc);

                });

                map.fitBounds(bounds, 120);

                /*
                * Use the selected address as the origin to
                * calculate distances to each of the store locations
                * */
                const rankedStores = await calculateDistances(map.data, originLocation);
                showStoresList(map.data, rankedStores);
            });

            document.getElementById('getLocation').addEventListener('click', function () {
                const button = this;
                input.value = 'Getting your location...';
                button.classList.add('is-loading');

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        button.classList.remove('is-loading');
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        // Create a new LatLng object with the user's current location
                        const userLocation = new google.maps.LatLng(lat, lng);
                        // get place from lat and lng
                        const geocoder = new google.maps.Geocoder();
                        geocoder.geocode({location: userLocation}, async (results, status) => {
                                input.value = results[0].formatted_address;
                                if (status === 'OK') {
                                    if (results[0]) {
                                        originMarker.setVisible(true);
                                        const place = results[0];
                                        originMarker.setPosition(userLocation);

                                        /*
                                         * Recenter the map to the selected address
                                         * */
                                        originLocation = place.geometry.location;

                                        const bounds = new google.maps.LatLngBounds();
                                        bounds.extend(originLocation);
                                        map.data.forEach((practice) => {
                                            const loc = practice.getGeometry().get();
                                            bounds.extend(loc);
                                        });

                                        map.fitBounds(bounds, 150);

                                        // Use the selected address as the origin to calculate distances
                                        // to each of the store locations
                                        const rankedStores = await calculateDistances(map.data, originLocation);
                                        showStoresList(map.data, rankedStores);
                                        button.classList.remove('is-loading');
                                    } else {
                                        console.log('No results found');
                                    }
                                }
                            }
                        );

                    }, function (error) {
                        alert('Please allow location access to use this feature.');
                        console.error("Error occurred while getting location: ", error.message);
                        input.value = '';
                    });
                } else {
                    alert("Geolocation is not supported by this browser.");
                    input.value = '';
                }


            })
        }
    }
}

/**
 * Use Distance Matrix API to calculate distance from origin to each practice.
 */
async function calculateDistances(data, origin) {

    const stores = [];
    const destinations = [];

    // Build parallel arrays for the store IDs and destinations
    data.forEach((store) => {
        const storeNum = store.getProperty('locationid');
        const storeLoc = store.getGeometry().get();
        stores.push(storeNum);
        destinations.push(storeLoc);
    });

    // Retrieve the distances of each store from the origin
    // The returned list will be in the same order as the destinations list
    const service = new google.maps.DistanceMatrixService();
    const getDistanceMatrix = (service, parameters) => new Promise((resolve, reject) => {
        service.getDistanceMatrix(parameters, (response, status) => {
            if (status != google.maps.DistanceMatrixStatus.OK) {
                reject(response);
            } else {
                const distances = [];
                const results = response.rows[0].elements;
                for (let j = 0; j < results.length; j++) {
                    const element = results[j];
                    const distanceText = element.distance.text;
                    const distanceValMiles = element.distance.value * 0.000621371;

                    if (distanceValMiles <= maxDistance) {

                        const distanceVal = element.distance.value;
                        const distanceObject = {
                            locationid: stores[j],
                            distanceText: distanceText,
                            distanceVal: distanceVal,
                        };
                        distances.push(distanceObject);
                    }
                }

                if (distances.length === 0) {
                    alert(`No practices found within ${maxDistance} miles of your location.`)
                } else {
                    resolve(distances);
                }
            }
        });
    });

    const distancesList = await getDistanceMatrix(service, {
        origins: [origin],
        destinations: destinations,
        travelMode: 'DRIVING',
        unitSystem: google.maps.UnitSystem.IMPERIAL,
    });

    distancesList.sort((first, second) => {
        return first.distanceVal - second.distanceVal;
    });


    return distancesList;
}

/**
 * Build the content of the side panel from the sorted list of stores
 * and display it.
 */
function showStoresList(data, stores) {
    if (stores.length === 0) {
        return;
    }

    const panel = document.getElementById('panel');

    // Clear the previous details
    while (panel.lastChild) {
        panel.removeChild(panel.lastChild);
    }

    stores.forEach((store) => {
        const currentStore = data.getFeatureById(store.locationid);
        const locationid = currentStore.getProperty('locationid');
        const name = currentStore.getProperty('name');
        const address = currentStore.getProperty('address');
        const phone = currentStore.getProperty('phone');
        const phoneHref = 'tel:' + phone.replace(/\D/g, '');
        const link = currentStore.getProperty('link');
        const distance = store.distanceText;
        const position = currentStore.getGeometry().get();
        const directions = `https://www.google.com/maps/dir/?api=1&destination=${position.lat()},${position.lng()}`;

        const resultsCard = `
          <div>
            <a href="#" class="open-info-window" data-locationid="${locationid}">
                <h3 class="location-title">${name}</h3>
            </a>
            <div class="location-data-container">
                <p>${address}</p>
                <p class="location-distance">Distance: ${distance}</p>
                <ul class="location-phone-list">
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0,0,256,256" width="25px" height="25px" fill-rule="nonzero" class="glacial-svg-icon">
                          <g fill="currentColor" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                            <g transform="scale(5.12,5.12)">
                              <path d="M11.83984,2.98828c-0.76953,-0.0625 -1.625,0.16016 -2.41406,0.71484c-0.69531,0.48438 -2.19531,1.67578 -3.59766,3.02344c-0.69922,0.67188 -1.36719,1.37109 -1.88281,2.05859c-0.51953,0.6875 -0.97266,1.31641 -0.94531,2.23047c0.02734,0.82031 0.10938,3.24609 1.85547,6.96484c1.74609,3.71484 5.13281,8.8125 11.73828,15.42188c6.60938,6.60938 11.70703,9.99609 15.42188,11.74219c3.71484,1.74609 6.14453,1.82813 6.96484,1.85547c0.91016,0.02734 1.53906,-0.42578 2.22656,-0.94531c0.6875,-0.51953 1.38672,-1.18359 2.05859,-1.88281c1.34375,-1.40234 2.53516,-2.90234 3.01953,-3.59766c1.10547,-1.57422 0.92188,-3.43359 -0.30859,-4.29687c-0.77344,-0.54297 -7.88672,-5.27734 -8.95703,-5.93359c-1.08594,-0.66406 -2.33594,-0.36328 -3.45312,0.22656c-0.87891,0.46484 -3.25781,1.82813 -3.9375,2.21875c-0.51172,-0.32422 -2.45312,-1.61719 -6.62891,-5.79297c-4.17969,-4.17578 -5.46875,-6.11719 -5.79297,-6.62891c0.39063,-0.67969 1.75,-3.04687 2.21875,-3.94141c0.58594,-1.11328 0.91406,-2.375 0.21484,-3.46875c-0.29297,-0.46484 -1.625,-2.49219 -2.96875,-4.52734c-1.34766,-2.03516 -2.625,-3.96484 -2.95703,-4.42578v-0.00391c-0.43359,-0.59766 -1.10937,-0.94922 -1.875,-1.01172zM11.65625,5.03125c0.27344,0.03516 0.4375,0.14453 0.4375,0.14453c0.16016,0.22266 1.5625,2.32422 2.90625,4.35547c1.34375,2.03516 2.71484,4.12109 2.95313,4.5c0.03906,0.05859 0.09375,0.72266 -0.29687,1.46094v0.00391c-0.44141,0.83984 -2.5,4.4375 -2.5,4.4375l-0.28516,0.50391l0.29297,0.5c0,0 1.53516,2.58984 6.41797,7.47266c4.88672,4.88281 7.47656,6.42188 7.47656,6.42188l0.5,0.29297l0.50391,-0.28516c0,0 3.58984,-2.05469 4.4375,-2.5c0.73828,-0.38672 1.40234,-0.33594 1.48047,-0.28906c0.69141,0.42578 8.375,5.53125 8.84766,5.86328c0.01563,0.01172 0.43359,0.64453 -0.17578,1.51172h-0.00391c-0.36719,0.52734 -1.57031,2.05469 -2.82422,3.35938c-0.62891,0.65234 -1.27344,1.26172 -1.82031,1.67188c-0.54687,0.41016 -1.03516,0.53906 -0.95703,0.54297c-0.85156,-0.02734 -2.73047,-0.04687 -6.17969,-1.66797c-3.44922,-1.61719 -8.37109,-4.85547 -14.85937,-11.34766c-6.48437,-6.48437 -9.72266,-11.40625 -11.34375,-14.85937c-1.61719,-3.44922 -1.63672,-5.32812 -1.66406,-6.17578c0.00391,0.07813 0.13281,-0.41406 0.54297,-0.96094c0.41016,-0.54687 1.01563,-1.19531 1.66797,-1.82422c1.30859,-1.25391 2.83203,-2.45703 3.35938,-2.82422v0.00391c0.43359,-0.30469 0.8125,-0.34375 1.08594,-0.3125z"></path>
                            </g>
                          </g>
                        </svg>
                        <a href="${phoneHref}">
                          <span class="nowrap">${phone}</span>
                        </a>
                    </li>
                </ul>
              <div class="location-info-buttons">
                  <a href="${link}" class="map-link">More Information</a>
                  <a href="${directions}" class="map-link" target="_blank" rel="noopener noreferrer" 
                     aria-label="Get Directions - Opens in new tab">Get Directions</a>
              </div>
            </div>
        </div>`;

        panel.innerHTML += resultsCard;
    });

}
