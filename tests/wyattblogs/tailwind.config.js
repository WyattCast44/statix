module.exports = {
	content: [
		"./resources/assets/css/**/*.css",
		"./resources/assets/js/**/*.js",
		"./resources/assets/public/**/*.*",
		"./resources/content/**/*.*",
		"./resources/views/**/*.blade.php",
	],
	theme: {
		extend: {
			keyframes: {
				wiggle: {
					"0%, 100%": { transform: "rotate(-3deg)" },
					"50%": { transform: "rotate(3deg)" },
				},
			},
			animation: {
				wiggle: "wiggle 1s ease-in-out infinite",
			},
		},
	},
	plugins: [require("@tailwindcss/typography")],
};
