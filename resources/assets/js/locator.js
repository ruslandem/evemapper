(function ($) {
    $.fn.locator = function (options) {
        var settings = $.extend(
            {
                interval: 20000,
            },
            options
        );

        this.init = () => {
            if (
                !settings.requestUrl ||
                !settings.callbackUrl ||
                !settings.currenSolarSystem ||
                !settings.interval
            ) {
                throw "insufficient data";
            }

            updateAutoLocationButton();

            return this;
        };

        this.update = () => {
            beforeUpdate();

            $.get(settings.requestUrl)
                .done(function (response) {
                    afterUpdate();
                    handleUpdate(response.solarSystemName);
                })
                .fail(function (response) {
                    throw "Failed to get location (" + response + ")";
                });
        };

        this.isAutoLocationEnabled = () => {
            return window.sessionStorage.getItem("autolocate") === "true";
        };

        this.setAutoLocation = (status) => {
            window.sessionStorage.setItem(
                "autolocate",
                status ? "true" : "false"
            );

            if (status === true) {
                window.sessionStorage.setItem(
                    "autoLocationInterval",
                    setInterval(() => {
                        this.update();
                    }, settings.interval)
                );
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

        this.toggleAutoLocation = () => {
            this.setAutoLocation(!this.isAutoLocationEnabled);
        };

        const updateAutoLocationButton = () => {
            this.isAutoLocationEnabled()
                ? $("#autolocate")
                      .removeClass("has-background-danger")
                      .addClass("has-background-success")
                : $("#autolocate")
                      .removeClass("has-background-success")
                      .addClass("has-background-danger");
        };

        const beforeUpdate = () => {
            $(this).find("a").attr("disabled", true);
            $(this).find("i.fa-rotate").addClass("fa-spin");
        };

        const afterUpdate = () => {
            $(this).find("a").attr("disabled", false);
            $(this).find("i.fa-rotate").removeClass("fa-spin");
        };

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
