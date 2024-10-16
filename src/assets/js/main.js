function docReady(fn) {
    // see if DOM is already available

    if (
        document.readyState === "complete" ||
        document.readyState === "interactive"
    ) {
        // call on next available tick
        setTimeout(fn, 1);
    } else {
        document.addEventListener("DOMContentLoaded", fn);
    }
}

let cancelFlash = false;
let flashWidth = 0;
let runningFlash = false;

docReady(() => {
    window
        .matchMedia("(prefers-color-scheme: dark)")
        .addEventListener("change", (e) => {
            e.matches ? setDarkMode() : setLightMode();
        });

    submitButton();
    eventListener();
    if (document.querySelector("body")?.classList.contains("device")) {
        if (
            window.matchMedia &&
            window.matchMedia("(prefers-color-scheme: dark)").matches
        ) {
            setDarkMode();
        } else {
            setLightMode();
        }
    } else {
        if (document.querySelector("body")?.classList.contains("dark")) {
            setDarkMode();
        } else {
            setLightMode();
        }
    }

    // document.querySelector('.flash-center').addEventListener('mouseover', (event) => {
    //    flashWidth = document.querySelector('.flash-center').clientWidth;
    // })

    document
        .querySelector(".flash-cancel")
        ?.addEventListener("click", (event) => {
            cancelFlash = true;
            runningFlash = false;
        });

    document.querySelector(".output")?.addEventListener("click", (event) => {
        var text = document
            ?.querySelector(".output")
            .innerHTML.replaceAll("&lt;", "<")
            .replaceAll("&gt;", ">")
            .replaceAll("&apos;", "'")
            .replaceAll("&quot;", '"')
            .replaceAll("\n", "")
            .replaceAll("\r", "")
            .replaceAll("&nbsp;", "  ");
        navigator.clipboard.writeText(text).then(
            function () {
                flash("successfully copied", "success");
            },
            function (err) {
                flash("something go wrong", "error");
            }
        );
    });
});

function eventListener() {
    const elements = document.querySelectorAll(".appereance-change");
    if (elements) {
        elements.forEach((element) => {
            element.addEventListener("click", (event) => {
                if (element.classList.contains("dark-change")) {
                    setLightMode();
                } else {
                    setDarkMode();
                }
            });
        });
    }
}

let skipProgress = false;

function submitButton() {
    document.querySelector(".submit").addEventListener("click", (event) => {
        convertData();
    });
}

function convertData() {
    if (document.querySelector("#input").value != "") {
        loadingProgress();
        const formData = new FormData();
        const url =
            document.querySelector("body").getAttribute("data-baseUrl") +
            "data/convertData.php";
        formData.append("action", "convertData");
        formData.append("data", document.querySelector("#input").value);
        const options = {
            method: "POST",
            body: formData,
        };
        fetch(url, options)
            .then((response) => response.json())
            .then((data) => {
                //   callback(data.result);
                skipProgress = true;
                document
                    .querySelector(".output")
                    .setAttribute("data-result", data.result);
                document.querySelector("#input").value = "";
                return data.result;
            });
    } else {
        flash("Please type some HTML", "error");
    }
}

function setLightMode() {
    document.querySelector(".light-change").style.display = "block";
    document.querySelector(".dark-change").style.display = "none";
    document.querySelector("body").setAttribute("class", "light");
    document.querySelector("#input").style.color = "black";
    document.cookie = "appearence=light";
}

function setDarkMode() {
    document.querySelector(".light-change").style.display = "none";
    document.querySelector(".dark-change").style.display = "block";
    document.querySelector("body").setAttribute("class", "dark");
    document.querySelector("#input").style.color = "white";
    document.cookie = "appearence=dark";
}

let currentWidth = 0;

function loadingProgress() {
    document
        .querySelector(".loading-Progress")
        .classList.replace("loading-hidden", "loading-show");
    document
        .querySelector(".loading-Progress")
        .classList.remove("loading-error");
    document.querySelector(".output").innerHTML = "";
    document.querySelector(".loading-Progress").style.width = "0px";
    currentWidth = 0;
    slowProgress();
}

function slowProgress() {
    const slowInterval = setInterval(() => {
        currentWidth += 2;
        /**
         * @type any
         */
        const loadingProgress = document.querySelector(".loading-Progress");
        if (loadingProgress) loadingProgress.style.width = currentWidth;
        if (skipProgress == true) {
            clearInterval(slowInterval);
            fastProgress();
        }

        if (currentWidth >= document.querySelector(".submit").clientWidth) {
            // document.querySelector('.loading-Progress').classList.add('loading-error');
            flash("Unable to convert", "error");
            clearInterval(slowInterval);
        }
    }, 10);
}

function fastProgress() {
    const fastInterval = setInterval(() => {
        currentWidth += 40;
        document.querySelector(".loading-Progress").style.width = currentWidth;
        if (currentWidth >= document.querySelector(".submit").clientWidth) {
            clearInterval(fastInterval);
            document.querySelector(".output").getAttribute("data-result") == ""
                ? flash("Unable to convert", "error")
                : false;
            document.querySelector(".output").innerHTML = document
                .querySelector(".output")
                .getAttribute("data-result");
            document.querySelector(".output").getAttribute("data-result") != ""
                ? document
                      .querySelector(".output")
                      .classList.add("output-filled")
                : "";
            document
                .querySelector(".loading-Progress")
                .classList.replace("loading-show", "loading-hidden");
        }
    }, 2);
}

function flash(message, mode) {
    const center = document.querySelector(".flash-center");
    const bar = document.querySelector(".flash-bar");

    switch (mode) {
        case "error": {
            console.log(mode);
            center.setAttribute("class", "flash-center flash-center-error");
            bar.setAttribute("class", "flash-bar flash-bar-error");
            break;
        }
        default: {
            center.setAttribute("class", "flash-center flash-center-success");
            bar.setAttribute("class", "flash-bar flash-bar-success");
            break;
        }
    }

    document.querySelector(".flash-container").style.display = "flex";
    document.querySelector(".flash-message").innerHTML = message;
    flashWidth = document.querySelector(".flash-center").clientWidth;

    if (!runningFlash) {
        flashProgress();
    }

    runningFlash = true;
}

function flashProgress() {
    const flashInterval = setInterval(() => {
        flashWidth -= 1;
        if (flashWidth <= 0 || cancelFlash) {
            clearInterval(flashInterval);
            document.querySelector(".flash-container").style.display = "none";
            runningFlash = false;
            cancelFlash = false;
        }
        document.querySelector(".flash-bar").style.width = flashWidth;
    }, 1);
}
