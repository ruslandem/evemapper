(function ($) {
    $.fn.locator = function (options) {
        var settings = $.extend(
            {
                interval: 20000,
            },
            options
        );

        /**
         * Initialization of plugin.
         * @returns this
         */
        this.init = () => {
            if (
                !settings.requestUrl ||
                !settings.callbackUrl ||
                settings.currenSolarSystem === "undefined" ||
                !settings.interval
            ) {
                throw "insufficient data";
            }

            this.setAutoLocation(this.isAutoLocationEnabled());

            return this;
        };

        /**
         * Update location and redirect page if neccessary.
         * @returns void
         */
        this.update = () => {
            beforeUpdate();
            this.getLocation()
                .done((response) => {
                    handleUpdate(response);
                })
                .fail(() => {
                    throw "Failed to get location";
                })
                .always(() => {
                    afterUpdate();
                });
        };

        /**
         * Get solar system name of current location.
         * @returns {Promise} Resolve a Deferred object and call any doneCallbacks with the given
         */
        this.getLocation = () => {
            var dfd = jQuery.Deferred();

            $.get(settings.requestUrl)
                .done((response) => {
                    dfd.resolve(response.solarSystemName);
                })
                .fail(() => {
                    dfd.fail();
                });

            return dfd.promise();
        };

        /**
         * Checks if auto-location is enabled.
         * @returns boolean
         */
        this.isAutoLocationEnabled = () => {
            return window.sessionStorage.getItem("autolocate") === "true";
        };

        /**
         * Sets auto-location enabled/disabled.
         * @param {boolean} status
         * @returns {boolean} Status
         */
        this.setAutoLocation = (status) => {
            window.sessionStorage.setItem(
                "autolocate",
                status ? "true" : "false"
            );

            if (status === true) {
                window.sessionStorage.autoLocationInterval = setInterval(() => {
                    this.update();
                }, 20000);
            } else {
                if (window.sessionStorage.autoLocationInterval) {
                    clearInterval(window.sessionStorage.autoLocationInterval);
                    window.sessionStorage.removeItem("autoLocationInterval");
                }
            }

            updateAutoLocationButton();

            Toastify({
                text: `Auto-location ${status ? "on" : "off"}`,
                duration: 3000,
            }).showToast();

            return status;
        };

        /**
         * Toggles auto-location enabled/disabled.
         */
        this.toggleAutoLocation = () => {
            this.setAutoLocation(!this.isAutoLocationEnabled());
        };

        /**
         * Update CSS classes for auto-location buttons.
         */
        const updateAutoLocationButton = () => {
            this.isAutoLocationEnabled()
                ? $("#autolocate")
                      .removeClass("has-background-danger")
                      .addClass("has-background-success")
                : $("#autolocate")
                      .removeClass("has-background-success")
                      .addClass("has-background-danger");
        };

        /**
         * Before update event.
         */
        const beforeUpdate = () => {
            $(this).find("a").attr("disabled", true);
            $(this).find("i.fa-rotate").addClass("fa-spin");
        };

        /**
         * After update event.
         */
        const afterUpdate = () => {
            $(this).find("a").attr("disabled", false);
            $(this).find("i.fa-rotate").removeClass("fa-spin");
        };

        /**
         * Checks if we need to redirect to a page with new solar system.
         * @param {string} solarSystemName
         * @returns
         */
        const handleUpdate = (solarSystemName) => {
            if (
                solarSystemName &&
                solarSystemName != settings.currenSolarSystem
            ) {
                window.location.href =
                    settings.callbackUrl + "/" + solarSystemName;
                return false;
            }
        };

        return this.init();
    };
})(jQuery);
