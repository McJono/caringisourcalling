const { EleventyHtmlBasePlugin } = require("@11ty/eleventy");

module.exports = function (eleventyConfig) {
  // Watch CSS for changes
  eleventyConfig.addWatchTarget("./src/assets/css/");

  // Passthrough copies
  eleventyConfig.addPassthroughCopy("src/assets");
  eleventyConfig.addPassthroughCopy({ "mail.php": "mail.php" });

  // Shortcodes
  eleventyConfig.addShortcode("year", () => `${new Date().getFullYear()}`);

  // Filters
  eleventyConfig.addFilter("striptags", (str) =>
    str ? str.replace(/<[^>]*>/g, "") : ""
  );

  // Plugins
  eleventyConfig.addPlugin(EleventyHtmlBasePlugin);

  return {
    dir: {
      input: "src",
      output: "_site",
      includes: "_includes",
      data: "_data",
    },
    markdownTemplateEngine: "md",
    htmlTemplateEngine: "html",
    templateFormats: ["html", "md", "njk"],
  };
};
