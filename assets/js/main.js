/**
 * Lakshmi Sridhar — UI interactions
 */
(function () {
  "use strict";

  document.documentElement.classList.add("js-ready");

  const header = document.querySelector("[data-header]");
  const nav = document.querySelector("[data-nav]");
  const navToggle = document.querySelector("[data-nav-toggle]");
  const dropdownToggles = document.querySelectorAll("[data-dropdown-toggle]");

  function setHeaderState() {
    if (!header) return;
    header.classList.toggle("is-scrolled", window.scrollY > 12);
  }

  setHeaderState();
  window.addEventListener("scroll", setHeaderState, { passive: true });

  if (navToggle && nav) {
    navToggle.addEventListener("click", function () {
      const open = !nav.classList.contains("is-open");
      nav.classList.toggle("is-open", open);
      navToggle.setAttribute("aria-expanded", String(open));
      const label = navToggle.querySelector(".nav-toggle__label");
      if (label) label.textContent = open ? "Close" : "Menu";
    });
  }

  dropdownToggles.forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const item = btn.closest(".has-dropdown");
      if (!item) return;
      const willOpen = !item.classList.contains("is-open");
      document.querySelectorAll(".has-dropdown.is-open").forEach(function (el) {
        if (el !== item) {
          el.classList.remove("is-open");
          const t = el.querySelector("[data-dropdown-toggle]");
          if (t) t.setAttribute("aria-expanded", "false");
        }
      });
      item.classList.toggle("is-open", willOpen);
      btn.setAttribute("aria-expanded", String(willOpen));
    });
  });

  document.addEventListener("click", function (e) {
    if (!e.target.closest(".has-dropdown")) {
      document.querySelectorAll(".has-dropdown.is-open").forEach(function (el) {
        el.classList.remove("is-open");
        const t = el.querySelector("[data-dropdown-toggle]");
        if (t) t.setAttribute("aria-expanded", "false");
      });
    }
  });

  /* FAQ accordion */
  document.querySelectorAll("[data-faq]").forEach(function (list) {
    list.querySelectorAll("[data-faq-toggle]").forEach(function (btn) {
      btn.addEventListener("click", function () {
        const item = btn.closest("[data-faq-item]");
        const panel = item && item.querySelector("[data-faq-panel]");
        if (!item || !panel) return;
        const open = item.classList.contains("is-open");

        list.querySelectorAll("[data-faq-item].is-open").forEach(function (other) {
          if (other === item) return;
          other.classList.remove("is-open");
          const otherBtn = other.querySelector("[data-faq-toggle]");
          const otherPanel = other.querySelector("[data-faq-panel]");
          if (otherBtn) otherBtn.setAttribute("aria-expanded", "false");
          if (otherPanel) otherPanel.hidden = true;
        });

        item.classList.toggle("is-open", !open);
        btn.setAttribute("aria-expanded", String(!open));
        panel.hidden = open;
      });
    });
  });

  /* Share */
  function showToast(message) {
    let toast = document.querySelector(".toast");
    if (!toast) {
      toast = document.createElement("div");
      toast.className = "toast";
      toast.setAttribute("role", "status");
      document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.classList.add("is-visible");
    clearTimeout(showToast._timer);
    showToast._timer = setTimeout(function () {
      toast.classList.remove("is-visible");
    }, 2200);
  }

  document.querySelectorAll("[data-share]").forEach(function (btn) {
    btn.addEventListener("click", async function () {
      const url = btn.getAttribute("data-share-url") || window.location.href;
      const title = btn.getAttribute("data-share-title") || document.title;

      if (navigator.share) {
        try {
          await navigator.share({ title: title, url: url });
          return;
        } catch (err) {
          if (err && err.name === "AbortError") return;
        }
      }

      try {
        await navigator.clipboard.writeText(url);
        showToast("Link copied");
      } catch (err) {
        showToast("Unable to copy link");
      }
    });
  });

  /* Contact form — front-end confirmation (no backend yet) */
  const form = document.querySelector("[data-contact-form]");
  if (form) {
    const params = new URLSearchParams(window.location.search);
    const subject = params.get("subject");
    const artwork = params.get("artwork");
    const course = params.get("course");
    const subjectField = form.querySelector('[name="subject"]');
    const messageField = form.querySelector('[name="message"]');

    if (subjectField && subject) {
      const options = Array.from(subjectField.options).map(function (o) {
        return o.value;
      });
      if (options.includes(subject)) {
        subjectField.value = subject;
      }
    }

    if (messageField) {
      const bits = [];
      if (artwork) bits.push("Regarding: " + artwork);
      if (course) bits.push("Course interest: " + course);
      if (bits.length && !messageField.value) {
        messageField.value = bits.join("\n") + "\n\n";
      }
    }

    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const status = form.querySelector("[data-form-status]");
      if (status) {
        status.textContent =
          "Thank you — your message is ready to send. Please replace the form handler with your email service, or message me on WhatsApp for a quicker reply.";
        status.classList.add("is-visible");
      }
      form.reset();
      if (subjectField && subject) subjectField.value = subject;
    });
  }
})();
