module.exports = {
  content: [
    './app/**/*.php',
    './public/**/*.php',
    './**/*.html'
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui']
      }
    }
  },
  safelist: [
    {
      pattern: /^(bg|text|border|ring|from|via|to|hover:bg|hover:text|hover:border|shadow|rounded|max-h|min-h|h|w|p|px|py|pt|pr|pb|pl|m|mx|my|mt|mr|mb|ml|gap|space-y|space-x|grid-cols|col-span|row-span|justify|items|self|place|inset|top|left|right|bottom|z|opacity|overflow|overscroll|backdrop|flex|inline-flex|hidden|block|relative|absolute|sticky|fixed|min-w|max-w|tracking|leading|font|uppercase|lowercase|capitalize|normal-case|whitespace|truncate|object|ring-offset|appearance|outline|transition|duration|ease|cursor|select|pointer-events|sr-only|divide|list|order|basis|grow|shrink|translate|scale|rotate|skew|origin|content|mix-blend|isolate|filter|blur|brightness|contrast|grayscale|hue-rotate|invert|saturate|sepia|drop-shadow)/,
    }
  ],
  plugins: []
};
