module.exports = {
	plugins: ["@babel/plugin-transform-runtime", "@babel/plugin-transform-destructuring"],
    presets:[
        "@babel/preset-react",
		["@babel/preset-env",
		  {
			"useBuiltIns": "entry"
		  }
		]
	]
}
  