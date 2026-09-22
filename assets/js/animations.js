/**
 * Lakshmi Sridhar — GSAP + ScrollTrigger animations
 * Slow, artistic, premium motion. Respects reduced motion.
 */
(function () {
  "use strict";

  if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") {
    document.querySelectorAll(".reveal").forEach(function (el) {
      el.style.opacity = "1";
    });
    return;
  }

  gsap.registerPlugin(ScrollTrigger);

  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  if (reduceMotion) {
    gsap.set(".reveal", { clearProps: "all", opacity: 1 });
    return;
  }

  gsap.defaults({
    ease: "power2.out",
    duration: 1.15,
  });

  /* Hero entrance */
  const heroTl = gsap.timeline({ defaults: { ease: "power3.out" } });
  const heroCopy = document.querySelectorAll(
    ".home-hero .reveal, .page-hero .reveal"
  );

  if (heroCopy.length) {
    gsap.set(heroCopy, { opacity: 0, y: 36 });
    heroTl.to(heroCopy, {
      opacity: 1,
      y: 0,
      duration: 1.35,
      stagger: 0.14,
      clearProps: "transform",
    });
  }

  const heroMedia = document.querySelector(
    ".home-hero__frame, .page-hero__frame"
  );
  if (heroMedia) {
    gsap.fromTo(
      heroMedia,
      { opacity: 0, y: 48, scale: 1.02 },
      { opacity: 1, y: 0, scale: 1, duration: 1.6, delay: 0.2, ease: "power3.out" }
    );
  }

  /* Scroll reveals */
  gsap.utils.toArray(".reveal").forEach(function (el) {
    if (el.closest(".home-hero") || el.closest(".page-hero")) return;

    gsap.fromTo(
      el,
      { opacity: 0, y: 42 },
      {
        opacity: 1,
        y: 0,
        duration: 1.2,
        ease: "power2.out",
        scrollTrigger: {
          trigger: el,
          start: "top 88%",
          toggleActions: "play none none none",
        },
        clearProps: "transform",
      }
    );
  });

  /* Staggered grids */
  document
    .querySelectorAll(".feature-grid, .course-grid, .testimonial-grid, .how-steps")
    .forEach(function (grid) {
      const items = grid.children;
      if (!items.length) return;
      gsap.fromTo(
        items,
        { opacity: 0, y: 36 },
        {
          opacity: 1,
          y: 0,
          duration: 1.1,
          stagger: 0.12,
          ease: "power2.out",
          scrollTrigger: {
            trigger: grid,
            start: "top 85%",
            toggleActions: "play none none none",
          },
          clearProps: "transform",
        }
      );
    });

  /* Image parallax — stronger travel, still premium/smooth */
  gsap.utils.toArray("[data-parallax-img]").forEach(function (img) {
    const frame = img.parentElement;
    if (!frame) return;

    gsap.fromTo(
      img,
      { yPercent: -16 },
      {
        yPercent: 16,
        ease: "none",
        scrollTrigger: {
          trigger: frame,
          start: "top bottom",
          end: "bottom top",
          scrub: 0.85,
        },
      }
    );
  });

  /* Soft section wash on cream bands */
  gsap.utils.toArray(".section--cream, .whatsapp-cta, .connect-strip").forEach(function (section) {
    gsap.fromTo(
      section,
      { backgroundPosition: "50% 0%" },
      {
        backgroundPosition: "50% 120%",
        ease: "none",
        scrollTrigger: {
          trigger: section,
          start: "top bottom",
          end: "bottom top",
          scrub: 0.9,
        },
      }
    );
  });
})();
