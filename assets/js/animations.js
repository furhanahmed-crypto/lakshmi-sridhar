/**
 * Lakshmi Sridhar — GSAP + ScrollTrigger
 * Word/letter reveals on headings only.
 * Other text and blocks use the same slide-in as images.
 */
(function () {
  "use strict";

  function isHeading(el) {
    return !!(el && /^H[1-4]$/.test(el.tagName));
  }

  function forceVisible(root) {
    var scope = root || document;
    scope.querySelectorAll(".reveal, .split-chars, .split-words").forEach(function (el) {
      el.style.opacity = "1";
      el.style.visibility = "visible";
      el.style.transform = "none";
    });
    scope.querySelectorAll(".word__inner").forEach(function (el) {
      el.style.opacity = "1";
      el.style.visibility = "visible";
      el.style.transform = "none";
    });
  }

  if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") {
    forceVisible();
    return;
  }

  gsap.registerPlugin(ScrollTrigger);

  if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
    forceVisible();
    return;
  }

  gsap.defaults({
    ease: "power2.out",
    duration: 1.05,
  });

  function escapeHtml(value) {
    return String(value)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;");
  }

  function splitText(el) {
    if (!el || !isHeading(el) || el.getAttribute("data-split-ready") === "1") return;
    var text = (el.textContent || "").replace(/\s+/g, " ").trim();
    if (!text) return;

    el.setAttribute("aria-label", text);
    el.setAttribute("data-split-ready", "1");
    el.innerHTML = text.split(" ").map(function (word) {
      return (
        '<span class="word" aria-hidden="true"><span class="word__inner">' +
        escapeHtml(word) +
        "</span></span>"
      );
    }).join(" ");
  }

  document.querySelectorAll("h1, h2, h3, h4").forEach(function (el) {
    if (el.classList.contains("split-chars") || el.classList.contains("split-words")) {
      splitText(el);
    }
  });

  function playSplit(el, delay) {
    if (!el || !isHeading(el)) return;
    gsap.set(el, { autoAlpha: 1, clearProps: "transform" });
    var inners = el.querySelectorAll(".word__inner");
    if (!inners.length) {
      slideIn(el, delay);
      return;
    }
    gsap.fromTo(
      inners,
      { yPercent: 110, opacity: 0 },
      {
        yPercent: 0,
        opacity: 1,
        duration: 0.8,
        delay: delay || 0,
        stagger: 0.045,
        ease: "power3.out",
        overwrite: "auto",
      }
    );
  }

  function slideIn(el, delay) {
    if (!el) return;
    gsap.fromTo(
      el,
      { autoAlpha: 0.01, y: 24 },
      {
        autoAlpha: 1,
        y: 0,
        duration: 1.05,
        delay: delay || 0,
        ease: "power3.out",
        clearProps: "transform",
        overwrite: "auto",
      }
    );
  }

  var heroEyebrow = document.querySelector(".home-hero .eyebrow, .page-hero .eyebrow");
  var heroTitle = document.querySelector(".home-hero__title, .page-hero__title");
  var heroSub = document.querySelector(".home-hero__subtitle, .page-hero__subtitle");
  var heroLede = document.querySelector(".page-hero__lede");
  var heroBody = document.querySelector(".home-hero__body");
  var heroBtns = document.querySelector(".home-hero .btn-row");
  var heroMedia = document.querySelector(".home-hero__media, .page-hero__media");
  var heroCaption = document.querySelector(".home-hero__caption");

  slideIn(heroEyebrow, 0.04);
  if (heroTitle) playSplit(heroTitle, 0.08);
  slideIn(heroSub, 0.22);
  slideIn(heroLede, 0.3);
  slideIn(heroBody, 0.4);
  slideIn(heroBtns, 0.52);

  if (heroMedia) {
    gsap.set(heroMedia, { autoAlpha: 1 });
    gsap.fromTo(
      heroMedia,
      { y: 24, scale: 1.015 },
      {
        y: 0,
        scale: 1,
        autoAlpha: 1,
        duration: 1.25,
        delay: 0.12,
        ease: "power3.out",
        clearProps: "transform",
        overwrite: "auto",
      }
    );
  }

  if (heroCaption) {
    slideIn(heroCaption, 0.85);
  }

  function whenInView(el, play) {
    if (!el) return;
    ScrollTrigger.create({
      trigger: el,
      start: "top 94%",
      once: true,
      onEnter: play,
      onEnterBack: play,
    });
  }

  gsap.utils.toArray("h1, h2, h3, h4").forEach(function (el) {
    if (el === heroTitle) return;
    if (!el.classList.contains("split-chars") && !el.classList.contains("split-words")) return;
    whenInView(el, function () {
      playSplit(el, 0);
    });
  });

  gsap.utils.toArray(".reveal").forEach(function (el) {
    if (el.closest(".home-hero") || el.closest(".page-hero")) return;
    if (isHeading(el)) return;
    if (el.classList.contains("split-chars") || el.classList.contains("split-words")) return;
    if (el.classList.contains("art-card") && el.closest(".art-grid--shop")) return;
    if (el.matches(".home-hero__media, .page-hero__media, .about-story__media, .product-detail__media")) {
      gsap.set(el, { autoAlpha: 1 });
      return;
    }

    whenInView(el, function () {
      slideIn(el, 0);
    });
  });

  document
    .querySelectorAll(".feature-grid, .course-grid, .testimonial-grid, .how-steps, .facts-grid, .art-grid--shop")
    .forEach(function (grid) {
      var items = Array.prototype.slice.call(grid.children);
      if (!items.length) return;
      whenInView(grid, function () {
        gsap.fromTo(
          items,
          { autoAlpha: 0.01, y: 22 },
          {
            autoAlpha: 1,
            y: 0,
            duration: 0.9,
            stagger: 0.08,
            ease: "power2.out",
            clearProps: "transform",
            overwrite: "auto",
          }
        );
      });
    });

  gsap.utils.toArray("[data-parallax-img]").forEach(function (img) {
    if (img.closest(".art-card--product, .art-grid--shop, .product-detail, .home-hero, .page-hero, .about-story")) {
      return;
    }
    var frame = img.parentElement;
    if (!frame) return;

    gsap.fromTo(
      img,
      { yPercent: -8 },
      {
        yPercent: 8,
        ease: "none",
        scrollTrigger: {
          trigger: frame,
          start: "top bottom",
          end: "bottom top",
          scrub: 0.9,
        },
      }
    );
  });

  function revealStragglers() {
    forceVisible();
    if (typeof ScrollTrigger !== "undefined") {
      ScrollTrigger.refresh();
    }
  }

  window.addEventListener("load", function () {
    ScrollTrigger.refresh();
    window.setTimeout(revealStragglers, 700);
  });
  window.setTimeout(revealStragglers, 1400);
})();
