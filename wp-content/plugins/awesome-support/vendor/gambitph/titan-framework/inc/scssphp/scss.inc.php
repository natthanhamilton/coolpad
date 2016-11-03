<?php
/**
 * SCSS compiler written in PHP
 *
 * @copyright 2012-2014 Leaf Corcoran
 *
 * @license   http://opensource.org/licenses/gpl-license GPL-3.0
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @link      http://leafo.net/scssphp
 */
/**
 * The scss compiler and parser.
 *
 * Converting SCSS to CSS is a three stage process. The incoming file is parsed
 * by `scss_parser` into a syntax tree, then it is compiled into another tree
 * representing the CSS structure by `scssc`. The CSS tree is fed into a
 * formatter, like `scss_formatter` which then outputs CSS as a string.
 *
 * During the first compile, all values are *reduced*, which means that their
 * types are brought to the lowest form before being dump as strings. This
 * handles math equations, variable dereferences, and the like.
 *
 * The `parse` function of `scssc` is the entry point.
 *
 * In summary:
 *
 * The `scssc` class creates an instance of the parser, feeds it SCSS code,
 * then transforms the resulting tree to a CSS tree. This class also holds the
 * evaluation context, such as all available mixins and variables at any given
 * time.
 *
 * The `scss_parser` class is only concerned with parsing its input.
 *
 * The `scss_formatter` takes a CSS tree, and dumps it to a formatted string,
 * handling things like indentation.
 */

/**
 * SCSS compiler
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
class titanscssc {
	static public    $VERSION        = 'v0.0.15';
	static public    $true           = ['keyword', 'true'];
	static public    $false          = ['keyword', 'false'];
	static public    $null           = ['null'];
	static public    $defaultValue   = ['keyword', ''];
	static public    $selfSelector   = ['self'];
	static protected $operatorNames
	                                 = [
			'+' => 'add',
			'-' => 'sub',
			'*' => 'mul',
			'/' => 'div',
			'%' => 'mod',
			'==' => 'eq',
			'!=' => 'neq',
			'<'  => 'lt',
			'>'  => 'gt',
			'<=' => 'lte',
			'>=' => 'gte',
		];
	static protected $namespaces
	                                 = [
			'special'  => '%',
			'mixin'    => '@',
			'function' => '^',
		];
	static protected $unitTable
	                                 = [
			'in' => [
				'in' => 1,
				'pt' => 72,
				'pc' => 6,
				'cm' => 2.54,
				'mm' => 25.4,
				'px' => 96,
			]
		];
	protected static $lib_if         = ['condition', 'if-true', 'if-false'];
	protected static $lib_index      = ['list', 'value'];
	protected static $lib_rgb        = ['red', 'green', 'blue'];
	protected static $lib_rgba
	                                 = [
			['red', 'color'],
			'green', 'blue', 'alpha'];
	protected static $lib_adjust_color
	                                 = [
			'color', 'red', 'green', 'blue',
			'hue', 'saturation', 'lightness', 'alpha'
		];
	protected static $lib_change_color
	                                 = [
			'color', 'red', 'green', 'blue',
			'hue', 'saturation', 'lightness', 'alpha'
		];
	protected static $lib_scale_color
	                                 = [
			'color', 'red', 'green', 'blue',
			'hue', 'saturation', 'lightness', 'alpha'
		];
	protected static $lib_ie_hex_str = ['color'];
	protected static $lib_red        = ['color'];
	protected static $lib_green      = ['color'];
	protected static $lib_blue       = ['color'];
	protected static $lib_alpha      = ['color'];
	protected static $lib_opacity    = ['color'];
	protected static $lib_mix        = ['color-1', 'color-2', 'weight'];
	protected static $lib_hsl        = ['hue', 'saturation', 'lightness'];
	protected static $lib_hsla
	                                 = ['hue', 'saturation',
	                                    'lightness', 'alpha'];
	protected static $lib_hue        = ['color'];
	// TODO refactor compileNestedBlock and compileMedia into same thing
	protected static $lib_saturation = ['color'];
	protected static $lib_lightness  = ['color'];
	// root level comment
	protected static $lib_adjust_hue = ['color', 'degrees'];
	// joins together .classes and #ids
	protected static $lib_lighten = ['color', 'amount'];
	// replaces all the interpolates
	protected static $lib_darken   = ['color', 'amount'];
	protected static $lib_saturate = ['color', 'amount'];
	// compiles to string
	// self(&) should have been replaced by now
	protected static $lib_desaturate = ['color', 'amount'];
	protected static $lib_grayscale  = ['color'];
	protected static $lib_complement = ['color'];
	protected static $lib_invert     = ['color'];
	protected static $lib_opacify    = ['color', 'amount'];
	protected static $lib_fade_in    = ['color', 'amount'];
	// returns true if the value was something that could be imported
	protected static $lib_transparentize = ['color', 'amount'];
	// return a value to halt execution
	protected static $lib_fade_out = ['color', 'amount'];
	protected static $lib_unquote  = ['string'];
	protected static $lib_quote    = ['string'];
	// should $value cause its operand to eval
	protected static $lib_percentage = ['value'];
	protected static $lib_round      = ['value'];
	protected static $lib_floor      = ['value'];
	// just does physical lengths for now
	protected static $lib_ceil = ['value'];
	// $number should be normalized
	protected static $lib_abs     = ['value'];
	protected static $lib_length  = ['list'];
	protected static $lib_nth     = ['list', 'n'];
	protected static $lib_join    = ['list1', 'list2', 'separator'];
	protected static $lib_append  = ['list', 'val', 'separator'];
	protected static $lib_type_of = ['value'];
	// adding strings
	protected static $lib_unit       = ['number'];
	protected static $lib_unitless   = ['number'];
	protected static $lib_comparable = ['number-1', 'number-2'];
	/**
	 * CSS Colors
	 *
	 * @see http://www.w3.org/TR/css3-color
	 */
	static protected $cssColors
		                              = [
			'aliceblue'            => '240,248,255',
			'antiquewhite'         => '250,235,215',
			'aqua'                 => '0,255,255',
			'aquamarine'           => '127,255,212',
			'azure'                => '240,255,255',
			'beige'                => '245,245,220',
			'bisque'               => '255,228,196',
			'black'                => '0,0,0',
			'blanchedalmond'       => '255,235,205',
			'blue'                 => '0,0,255',
			'blueviolet'           => '138,43,226',
			'brown'                => '165,42,42',
			'burlywood'            => '222,184,135',
			'cadetblue'            => '95,158,160',
			'chartreuse'           => '127,255,0',
			'chocolate'            => '210,105,30',
			'coral'                => '255,127,80',
			'cornflowerblue'       => '100,149,237',
			'cornsilk'             => '255,248,220',
			'crimson'              => '220,20,60',
			'cyan'                 => '0,255,255',
			'darkblue'             => '0,0,139',
			'darkcyan'             => '0,139,139',
			'darkgoldenrod'        => '184,134,11',
			'darkgray'             => '169,169,169',
			'darkgreen'            => '0,100,0',
			'darkgrey'             => '169,169,169',
			'darkkhaki'            => '189,183,107',
			'darkmagenta'          => '139,0,139',
			'darkolivegreen'       => '85,107,47',
			'darkorange'           => '255,140,0',
			'darkorchid'           => '153,50,204',
			'darkred'              => '139,0,0',
			'darksalmon'           => '233,150,122',
			'darkseagreen'         => '143,188,143',
			'darkslateblue'        => '72,61,139',
			'darkslategray'        => '47,79,79',
			'darkslategrey'        => '47,79,79',
			'darkturquoise'        => '0,206,209',
			'darkviolet'           => '148,0,211',
			'deeppink'             => '255,20,147',
			'deepskyblue'          => '0,191,255',
			'dimgray'              => '105,105,105',
			'dimgrey'              => '105,105,105',
			'dodgerblue'           => '30,144,255',
			'firebrick'            => '178,34,34',
			'floralwhite'          => '255,250,240',
			'forestgreen'          => '34,139,34',
			'fuchsia'              => '255,0,255',
			'gainsboro'            => '220,220,220',
			'ghostwhite'           => '248,248,255',
			'gold'                 => '255,215,0',
			'goldenrod'            => '218,165,32',
			'gray'                 => '128,128,128',
			'green'                => '0,128,0',
			'greenyellow'          => '173,255,47',
			'grey'                 => '128,128,128',
			'honeydew'             => '240,255,240',
			'hotpink'              => '255,105,180',
			'indianred'            => '205,92,92',
			'indigo'               => '75,0,130',
			'ivory'                => '255,255,240',
			'khaki'                => '240,230,140',
			'lavender'             => '230,230,250',
			'lavenderblush'        => '255,240,245',
			'lawngreen'            => '124,252,0',
			'lemonchiffon'         => '255,250,205',
			'lightblue'            => '173,216,230',
			'lightcoral'           => '240,128,128',
			'lightcyan'            => '224,255,255',
			'lightgoldenrodyellow' => '250,250,210',
			'lightgray'            => '211,211,211',
			'lightgreen'           => '144,238,144',
			'lightgrey'            => '211,211,211',
			'lightpink'            => '255,182,193',
			'lightsalmon'          => '255,160,122',
			'lightseagreen'        => '32,178,170',
			'lightskyblue'         => '135,206,250',
			'lightslategray'       => '119,136,153',
			'lightslategrey'       => '119,136,153',
			'lightsteelblue'       => '176,196,222',
			'lightyellow'          => '255,255,224',
			'lime'                 => '0,255,0',
			'limegreen'            => '50,205,50',
			'linen'                => '250,240,230',
			'magenta'              => '255,0,255',
			'maroon'               => '128,0,0',
			'mediumaquamarine'     => '102,205,170',
			'mediumblue'           => '0,0,205',
			'mediumorchid'         => '186,85,211',
			'mediumpurple'         => '147,112,219',
			'mediumseagreen'       => '60,179,113',
			'mediumslateblue'      => '123,104,238',
			'mediumspringgreen'    => '0,250,154',
			'mediumturquoise'      => '72,209,204',
			'mediumvioletred'      => '199,21,133',
			'midnightblue'         => '25,25,112',
			'mintcream'            => '245,255,250',
			'mistyrose'            => '255,228,225',
			'moccasin'             => '255,228,181',
			'navajowhite'          => '255,222,173',
			'navy'                 => '0,0,128',
			'oldlace'              => '253,245,230',
			'olive'                => '128,128,0',
			'olivedrab'            => '107,142,35',
			'orange'               => '255,165,0',
			'orangered'            => '255,69,0',
			'orchid'               => '218,112,214',
			'palegoldenrod'        => '238,232,170',
			'palegreen'            => '152,251,152',
			'paleturquoise'        => '175,238,238',
			'palevioletred'        => '219,112,147',
			'papayawhip'           => '255,239,213',
			'peachpuff'            => '255,218,185',
			'peru'                 => '205,133,63',
			'pink'                 => '255,192,203',
			'plum'                 => '221,160,221',
			'powderblue'           => '176,224,230',
			'purple'               => '128,0,128',
			'red'                  => '255,0,0',
			'rosybrown'            => '188,143,143',
			'royalblue'            => '65,105,225',
			'saddlebrown'          => '139,69,19',
			'salmon'               => '250,128,114',
			'sandybrown'           => '244,164,96',
			'seagreen'             => '46,139,87',
			'seashell'             => '255,245,238',
			'sienna'               => '160,82,45',
			'silver'               => '192,192,192',
			'skyblue'              => '135,206,235',
			'slateblue'            => '106,90,205',
			'slategray'            => '112,128,144',
			'slategrey'            => '112,128,144',
			'snow'                 => '255,250,250',
			'springgreen'          => '0,255,127',
			'steelblue'            => '70,130,180',
			'tan'                  => '210,180,140',
			'teal'                 => '0,128,128',
			'thistle'              => '216,191,216',
			'tomato'               => '255,99,71',
			'transparent'          => '0,0,0,0',
			'turquoise'            => '64,224,208',
			'violet'               => '238,130,238',
			'wheat'                => '245,222,179',
			'white'                => '255,255,255',
			'whitesmoke'           => '245,245,245',
			'yellow'               => '255,255,0',
			'yellowgreen'          => '154,205,50'
		];
	protected        $importPaths     = [''];
	protected        $importCache     = [];
	protected        $userFunctions   = [];
	protected        $registeredVars  = [];
	protected        $numberPrecision = 5;
	protected        $formatter       = 'titanscss_formatter_nested';

	/**
	 * Compile scss
	 *
	 * @param string $code
	 * @param string $name
	 *
	 * @return string
	 */
	public function compile($code, $name = NULL) {
		$this->indentLevel  = -1;
		$this->commentsSeen = [];
		$this->extends      = [];
		$this->extendsMap   = [];
		$this->parsedFiles  = [];
		$this->env          = NULL;
		$this->scope        = NULL;
		$locale = setlocale(LC_NUMERIC, 0);
		setlocale(LC_NUMERIC, 'C');
		$this->parser = new titanscss_parser($name);
		$tree = $this->parser->parse($code);
		$this->formatter = new $this->formatter();
		$this->pushEnv($tree);
		$this->injectVariables($this->registeredVars);
		$this->compileRoot($tree);
		$this->popEnv();
		$out = $this->formatter->format($this->scope);
		setlocale(LC_NUMERIC, $locale);

		return $out;
	}

	protected function pushEnv($block = NULL) {
		$env         = new stdClass;
		$env->parent = $this->env;
		$env->store  = [];
		$env->block  = $block;
		$env->depth  = isset($this->env->depth) ? $this->env->depth + 1 : 0;
		$this->env = $env;

		return $env;
	}

	protected function injectVariables(array $args) {
		if (empty($args)) {
			return;
		}
		$parser = new titanscss_parser(__METHOD__, FALSE);
		foreach ($args as $name => $strValue) {
			if ($name[0] == '$') {
				$name = substr($name, 1);
			}
			$parser->env             = NULL;
			$parser->count           = 0;
			$parser->buffer          = (string)$strValue;
			$parser->inParens        = FALSE;
			$parser->eatWhiteDefault = TRUE;
			$parser->insertComments  = TRUE;
			if (!$parser->valueList($value)) {
				throw new Exception("failed to parse passed in variable $name: $strValue");
			}
			$this->set($name, $value);
		}
	}

	protected function set($name, $value, $shadow = FALSE, $env = NULL) {
		$name = $this->normalizeName($name);
		if ($shadow) {
			$this->setRaw($name, $value, $env);
		} else {
			$this->setExisting($name, $value, $env);
		}
	}

	protected function normalizeName($name) {
		return str_replace('-', '_', $name);
	}

	// doesn't need to be recursive, compileValue will handle that
	protected function setRaw($name, $value, $env = NULL) {
		if (!isset($env)) $env = $this->getStoreEnv();
		$env->store[ $name ] = $value;
	}

	// find the final set of selectors
	protected function getStoreEnv() {
		return isset($this->storeEnv) ? $this->storeEnv : $this->env;
	}

	// looks for & to replace, or append parent before child
	protected function setExisting($name, $value, $env = NULL) {
		if (!isset($env)) $env = $this->getStoreEnv();
		if (isset($env->store[ $name ]) || !isset($env->parent)) {
			$env->store[ $name ] = $value;
		} else {
			$this->setExisting($name, $value, $env->parent);
		}
	}

	protected function compileRoot($rootBlock) {
		$this->scope = $this->makeOutputBlock('root');
		$this->compileChildren($rootBlock->children, $this->scope);
		$this->flattenSelectors($this->scope);
	}

	// convert something to list
	protected function makeOutputBlock($type, $selectors = NULL) {
		$out            = new stdClass;
		$out->type      = $type;
		$out->lines     = [];
		$out->children  = [];
		$out->parent    = $this->scope;
		$out->selectors = $selectors;
		$out->depth     = $this->env->depth;

		return $out;
	}

	protected function compileChildren($stms, $out) {
		foreach ($stms as $stm) {
			$ret = $this->compileChild($stm, $out);
			if (isset($ret)) return $ret;
		}
	}

	protected function compileChild($child, $out) {
		$this->sourcePos    = isset($child[ -1 ]) ? $child[ -1 ] : -1;
		$this->sourceParser = isset($child[ -2 ]) ? $child[ -2 ] : $this->parser;
		switch ($child[0]) {
			case 'import':
				list(, $rawPath) = $child;
				$rawPath = $this->reduce($rawPath);
				if (!$this->compileImport($rawPath, $out)) {
					$out->lines[] = '@import ' . $this->compileValue($rawPath) . ';';
				}
				break;
			case 'directive':
				list(, $directive) = $child;
				$s = '@' . $directive->name;
				if (!empty($directive->value)) {
					$s .= ' ' . $this->compileValue($directive->value);
				}
				$this->compileNestedBlock($directive, [$s]);
				break;
			case 'media':
				$this->compileMedia($child[1]);
				break;
			case 'block':
				$this->compileBlock($child[1]);
				break;
			case 'charset':
				$out->lines[] = '@charset ' . $this->compileValue($child[1]) . ';';
				break;
			case 'assign':
				list(, $name, $value) = $child;
				if ($name[0] == 'var') {
					$isDefault = !empty($child[3]);
					if ($isDefault) {
						$existingValue = $this->get($name[1], TRUE);
						$shouldSet     = $existingValue == TRUE || $existingValue == self::$null;
					}
					if (!$isDefault || $shouldSet) {
						$this->set($name[1], $this->reduce($value));
					}
					break;
				}
				// if the value reduces to null from something else then
				// the property should be discarded
				if ($value[0] != 'null') {
					$value = $this->reduce($value);
					if ($value[0] == 'null') {
						break;
					}
				}
				$compiledValue = $this->compileValue($value);
				$out->lines[]  = $this->formatter->property(
					$this->compileValue($name),
					$compiledValue);
				break;
			case 'comment':
				if ($out->type == 'root') {
					$this->compileComment($child);
					break;
				}
				$out->lines[] = $child[1];
				break;
			case 'mixin':
			case 'function':
				list(, $block) = $child;
				$this->set(self::$namespaces[ $block->type ] . $block->name, $block);
				break;
			case 'extend':
				list(, $selectors) = $child;
				foreach ($selectors as $sel) {
					// only use the first one
					$sel = current($this->evalSelector($sel));
					$this->pushExtends($sel, $out->selectors);
				}
				break;
			case 'if':
				list(, $if) = $child;
				if ($this->isTruthy($this->reduce($if->cond, TRUE))) {
					return $this->compileChildren($if->children, $out);
				} else {
					foreach ($if->cases as $case) {
						if ($case->type == 'else' ||
						    $case->type == 'elseif' && $this->isTruthy($this->reduce($case->cond))
						) {
							return $this->compileChildren($case->children, $out);
						}
					}
				}
				break;
			case 'return':
				return $this->reduce($child[1], TRUE);
			case 'each':
				list(, $each) = $child;
				$list = $this->coerceList($this->reduce($each->list));
				foreach ($list[2] as $item) {
					$this->pushEnv();
					$this->set($each->var, $item);
					// TODO: allow return from here
					$this->compileChildren($each->children, $out);
					$this->popEnv();
				}
				break;
			case 'while':
				list(, $while) = $child;
				while ($this->isTruthy($this->reduce($while->cond, TRUE))) {
					$ret = $this->compileChildren($while->children, $out);
					if ($ret) return $ret;
				}
				break;
			case 'for':
				list(, $for) = $child;
				$start = $this->reduce($for->start, TRUE);
				$start = $start[1];
				$end   = $this->reduce($for->end, TRUE);
				$end   = $end[1];
				$d     = $start < $end ? 1 : -1;
				while (TRUE) {
					if ((!$for->until && $start - $d == $end) ||
					    ($for->until && $start == $end)
					) {
						break;
					}
					$this->set($for->var, ['number', $start, '']);
					$start += $d;
					$ret = $this->compileChildren($for->children, $out);
					if ($ret) return $ret;
				}
				break;
			case 'nestedprop':
				list(, $prop) = $child;
				$prefixed = [];
				$prefix   = $this->compileValue($prop->prefix) . '-';
				foreach ($prop->children as $child) {
					if ($child[0] == 'assign') {
						array_unshift($child[1][2], $prefix);
					}
					if ($child[0] == 'nestedprop') {
						array_unshift($child[1]->prefix[2], $prefix);
					}
					$prefixed[] = $child;
				}
				$this->compileChildren($prefixed, $out);
				break;
			case 'include': // including a mixin
				list(, $name, $argValues, $content) = $child;
				$mixin = $this->get(self::$namespaces['mixin'] . $name, FALSE);
				if (!$mixin) {
					$this->throwError("Undefined mixin $name");
				}
				$callingScope = $this->env;
				// push scope, apply args
				$this->pushEnv();
				if ($this->env->depth > 0) {
					$this->env->depth--;
				}
				if (isset($content)) {
					$content->scope = $callingScope;
					$this->setRaw(self::$namespaces['special'] . 'content', $content);
				}
				if (isset($mixin->args)) {
					$this->applyArguments($mixin->args, $argValues);
				}
				foreach ($mixin->children as $child) {
					$this->compileChild($child, $out);
				}
				$this->popEnv();
				break;
			case 'mixin_content':
				$content = $this->get(self::$namespaces['special'] . 'content');
				if (!isset($content)) {
					$this->throwError('Expected @content inside of mixin');
				}
				$strongTypes = ['include', 'block', 'for', 'while'];
				foreach ($content->children as $child) {
					$this->storeEnv = (in_array($child[0], $strongTypes))
						? NULL
						: $content->scope;
					$this->compileChild($child, $out);
				}
				unset($this->storeEnv);
				break;
			case 'debug':
				list(, $value, $pos) = $child;
				$line  = $this->parser->getLineNo($pos);
				$value = $this->compileValue($this->reduce($value, TRUE));
				fwrite(STDERR, "Line $line DEBUG: $value\n");
				break;
			default:
				$this->throwError("unknown child type: $child[0]");
		}
	}

	protected function reduce($value, $inExp = FALSE) {
		list($type) = $value;
		switch ($type) {
			case 'exp':
				list(, $op, $left, $right, $inParens) = $value;
				$opName = isset(self::$operatorNames[ $op ]) ? self::$operatorNames[ $op ] : $op;
				$inExp = $inExp || $this->shouldEval($left) || $this->shouldEval($right);
				$left  = $this->reduce($left, TRUE);
				$right = $this->reduce($right, TRUE);
				// only do division in special cases
				if ($opName == 'div' && !$inParens && !$inExp) {
					if ($left[0] != 'color' && $right[0] != 'color') {
						return $this->expToString($value);
					}
				}
				$left  = $this->coerceForExpression($left);
				$right = $this->coerceForExpression($right);
				$ltype = $left[0];
				$rtype = $right[0];
				// this tries:
				// 1. op_[op name]_[left type]_[right type]
				// 2. op_[left type]_[right type] (passing the op as first arg
				// 3. op_[op name]
				$fn = "op_${opName}_${ltype}_${rtype}";
				if (is_callable([$this, $fn]) ||
				    (($fn = "op_${ltype}_${rtype}") &&
				     is_callable([$this, $fn]) &&
				     $passOp = TRUE) ||
				    (($fn = "op_${opName}") &&
				     is_callable([$this, $fn]) &&
				     $genOp = TRUE)
				) {
					$unitChange = FALSE;
					if (!isset($genOp) &&
					    $left[0] == 'number' && $right[0] == 'number'
					) {
						if ($opName == 'mod' && $right[2] != '') {
							$this->throwError("Cannot modulo by a number with units: $right[1]$right[2].");
						}
						$unitChange = TRUE;
						$emptyUnit  = $left[2] == '' || $right[2] == '';
						$targetUnit = '' != $left[2] ? $left[2] : $right[2];
						if ($opName != 'mul') {
							$left[2]  = '' != $left[2] ? $left[2] : $targetUnit;
							$right[2] = '' != $right[2] ? $right[2] : $targetUnit;
						}
						if ($opName != 'mod') {
							$left  = $this->normalizeNumber($left);
							$right = $this->normalizeNumber($right);
						}
						if ($opName == 'div' && !$emptyUnit && $left[2] == $right[2]) {
							$targetUnit = '';
						}
						if ($opName == 'mul') {
							$left[2]  = '' != $left[2] ? $left[2] : $right[2];
							$right[2] = '' != $right[2] ? $right[2] : $left[2];
						} elseif ($opName == 'div' && $left[2] == $right[2]) {
							$left[2]  = '';
							$right[2] = '';
						}
					}
					$shouldEval = $inParens || $inExp;
					if (isset($passOp)) {
						$out = $this->$fn($op, $left, $right, $shouldEval);
					} else {
						$out = $this->$fn($left, $right, $shouldEval);
					}
					if (isset($out)) {
						if ($unitChange && $out[0] == 'number') {
							$out = $this->coerceUnit($out, $targetUnit);
						}

						return $out;
					}
				}

				return $this->expToString($value);
			case 'unary':
				list(, $op, $exp, $inParens) = $value;
				$inExp = $inExp || $this->shouldEval($exp);
				$exp = $this->reduce($exp);
				if ($exp[0] == 'number') {
					switch ($op) {
						case '+':
							return $exp;
						case '-':
							$exp[1] *= -1;

							return $exp;
					}
				}
				if ($op == 'not') {
					if ($inExp || $inParens) {
						if ($exp == self::$false) {
							return self::$true;
						} else {
							return self::$false;
						}
					} else {
						$op = $op . ' ';
					}
				}

				return ['string', '', [$op, $exp]];
			case 'var':
				list(, $name) = $value;

				return $this->reduce($this->get($name));
			case 'list':
				foreach ($value[2] as &$item) {
					$item = $this->reduce($item);
				}

				return $value;
			case 'string':
				foreach ($value[2] as &$item) {
					if (is_array($item)) {
						$item = $this->reduce($item);
					}
				}

				return $value;
			case 'interpolate':
				$value[1] = $this->reduce($value[1]);

				return $value;
			case 'fncall':
				list(, $name, $argValues) = $value;
				// user defined function?
				$func = $this->get(self::$namespaces['function'] . $name, FALSE);
				if ($func) {
					$this->pushEnv();
					// set the args
					if (isset($func->args)) {
						$this->applyArguments($func->args, $argValues);
					}
					// throw away lines and children
					$tmp = (object)[
						'lines'    => [],
						'children' => []
					];
					$ret = $this->compileChildren($func->children, $tmp);
					$this->popEnv();

					return !isset($ret) ? self::$defaultValue : $ret;
				}
				// built in function
				if ($this->callBuiltin($name, $argValues, $returnValue)) {
					return $returnValue;
				}
				// need to flatten the arguments into a list
				$listArgs = [];
				foreach ((array)$argValues as $arg) {
					if (empty($arg[0])) {
						$listArgs[] = $this->reduce($arg[1]);
					}
				}

				return ['function', $name, ['list', ',', $listArgs]];
			default:
				return $value;
		}
	}

	protected function shouldEval($value) {
		switch ($value[0]) {
			case 'exp':
				if ($value[1] == '/') {
					return $this->shouldEval($value[2], $value[3]);
				}
			case 'var':
			case 'fncall':
				return TRUE;
		}

		return FALSE;
	}

	protected function expToString($exp) {
		list(, $op, $left, $right, $inParens, $whiteLeft, $whiteRight) = $exp;
		$content = [$this->reduce($left)];
		if ($whiteLeft) $content[] = ' ';
		$content[] = $op;
		if ($whiteRight) $content[] = ' ';
		$content[] = $this->reduce($right);

		return ['string', '', $content];
	}

	protected function coerceForExpression($value) {
		if ($color = $this->coerceColor($value)) {
			return $color;
		}

		return $value;
	}

	protected function coerceColor($value) {
		switch ($value[0]) {
			case 'color':
				return $value;
			case 'keyword':
				$name = $value[1];
				if (isset(self::$cssColors[ $name ])) {
					$rgba = explode(',', self::$cssColors[ $name ]);

					return isset($rgba[3])
						? ['color', (int)$rgba[0], (int)$rgba[1], (int)$rgba[2], (int)$rgba[3]]
						: ['color', (int)$rgba[0], (int)$rgba[1], (int)$rgba[2]];
				}

				return NULL;
		}

		return NULL;
	}

	public function throwError($msg = NULL) {
		if (func_num_args() > 1) {
			$msg = call_user_func_array('sprintf', func_get_args());
		}
		if ($this->sourcePos >= 0 && isset($this->sourceParser)) {
			$this->sourceParser->throwParseError($msg, $this->sourcePos);
		}
		throw new Exception($msg);
	}

	protected function normalizeNumber($number) {
		list(, $value, $unit) = $number;
		if (isset(self::$unitTable['in'][ $unit ])) {
			$conv = self::$unitTable['in'][ $unit ];

			return ['number', $value / $conv, 'in'];
		}

		return $number;
	}

	protected function coerceUnit($number, $unit) {
		list(, $value, $baseUnit) = $number;
		if (isset(self::$unitTable[ $baseUnit ][ $unit ])) {
			$value = $value * self::$unitTable[ $baseUnit ][ $unit ];
		}

		return ['number', $value, $unit];
	}

	public function get($name, $defaultValue = NULL, $env = NULL) {
		$name = $this->normalizeName($name);
		if (!isset($env)) $env = $this->getStoreEnv();
		if (!isset($defaultValue)) $defaultValue = self::$defaultValue;
		if (isset($env->store[ $name ])) {
			return $env->store[ $name ];
		} elseif (isset($env->parent)) {
			return $this->get($name, $defaultValue, $env->parent);
		}

		return $defaultValue; // found nothing
	}

	protected function applyArguments($argDef, $argValues) {
		$storeEnv = $this->getStoreEnv();
		$env        = new stdClass;
		$env->store = $storeEnv->store;
		$hasVariable = FALSE;
		$args        = [];
		foreach ($argDef as $i => $arg) {
			list($name, $default, $isVariable) = $argDef[ $i ];
			$args[ $name ] = [$i, $name, $default, $isVariable];
			$hasVariable |= $isVariable;
		}
		$keywordArgs         = [];
		$deferredKeywordArgs = [];
		$remaining           = [];
		// assign the keyword args
		foreach ((array)$argValues as $arg) {
			if (!empty($arg[0])) {
				if (!isset($args[ $arg[0][1] ])) {
					if ($hasVariable) {
						$deferredKeywordArgs[ $arg[0][1] ] = $arg[1];
					} else {
						$this->throwError("Mixin or function doesn't have an argument named $%s.", $arg[0][1]);
					}
				} elseif ($args[ $arg[0][1] ][0] < count($remaining)) {
					$this->throwError("The argument $%s was passed both by position and by name.", $arg[0][1]);
				} else {
					$keywordArgs[ $arg[0][1] ] = $arg[1];
				}
			} elseif (count($keywordArgs)) {
				$this->throwError('Positional arguments must come before keyword arguments.');
			} elseif ($arg[2] == TRUE) {
				$val = $this->reduce($arg[1], TRUE);
				if ($val[0] == 'list') {
					foreach ($val[2] as $name => $item) {
						if (!is_numeric($name)) {
							$keywordArgs[ $name ] = $item;
						} else {
							$remaining[] = $item;
						}
					}
				} else {
					$remaining[] = $val;
				}
			} else {
				$remaining[] = $arg[1];
			}
		}
		foreach ($args as $arg) {
			list($i, $name, $default, $isVariable) = $arg;
			if ($isVariable) {
				$val = ['list', ',', []];
				for ($count = count($remaining); $i < $count; $i++) {
					$val[2][] = $remaining[ $i ];
				}
				foreach ($deferredKeywordArgs as $itemName => $item) {
					$val[2][ $itemName ] = $item;
				}
			} elseif (isset($remaining[ $i ])) {
				$val = $remaining[ $i ];
			} elseif (isset($keywordArgs[ $name ])) {
				$val = $keywordArgs[ $name ];
			} elseif (!empty($default)) {
				continue;
			} else {
				$this->throwError("Missing argument $name");
			}
			$this->set($name, $this->reduce($val, TRUE), TRUE, $env);
		}
		$storeEnv->store = $env->store;
		foreach ($args as $arg) {
			list($i, $name, $default, $isVariable) = $arg;
			if ($isVariable || isset($remaining[ $i ]) || isset($keywordArgs[ $name ]) || empty($default)) {
				continue;
			}
			$this->set($name, $this->reduce($default, TRUE), TRUE);
		}
	}

	protected function popEnv() {
		$env       = $this->env;
		$this->env = $this->env->parent;

		return $env;
	}

	protected function callBuiltin($name, $args, &$returnValue) {
		// try a lib function
		$name    = $this->normalizeName($name);
		$libName = 'lib_' . $name;
		$f       = [$this, $libName];
		if (is_callable($f)) {
			$prototype = isset(self::$$libName) ? self::$$libName : NULL;
			$sorted    = $this->sortArgs($prototype, $args);
			foreach ($sorted as &$val) {
				$val = $this->reduce($val, TRUE);
			}
			$returnValue = call_user_func($f, $sorted, $this);
		} elseif (isset($this->userFunctions[ $name ])) {
			// see if we can find a user function
			$fn = $this->userFunctions[ $name ];
			foreach ($args as &$val) {
				$val = $this->reduce($val[1], TRUE);
			}
			$returnValue = call_user_func($fn, $args, $this);
		}
		if (isset($returnValue)) {
			// coerce a php value into a scss one
			if (is_numeric($returnValue)) {
				$returnValue = ['number', $returnValue, ''];
			} elseif (is_bool($returnValue)) {
				$returnValue = $returnValue ? self::$true : self::$false;
			} elseif (!is_array($returnValue)) {
				$returnValue = ['keyword', $returnValue];
			}

			return TRUE;
		}

		return FALSE;
	}

	protected function sortArgs($prototype, $args) {
		$keyArgs = [];
		$posArgs = [];
		foreach ($args as $arg) {
			list($key, $value) = $arg;
			$key = $key[1];
			if (empty($key)) {
				$posArgs[] = $value;
			} else {
				$keyArgs[ $key ] = $value;
			}
		}
		if (!isset($prototype)) return $posArgs;
		$finalArgs = [];
		foreach ($prototype as $i => $names) {
			if (isset($posArgs[ $i ])) {
				$finalArgs[] = $posArgs[ $i ];
				continue;
			}
			$set = FALSE;
			foreach ((array)$names as $name) {
				if (isset($keyArgs[ $name ])) {
					$finalArgs[] = $keyArgs[ $name ];
					$set         = TRUE;
					break;
				}
			}
			if (!$set) {
				$finalArgs[] = NULL;
			}
		}

		return $finalArgs;
	}

	protected function compileImport($rawPath, $out) {
		if ($rawPath[0] == 'string') {
			$path = $this->compileStringContent($rawPath);
			if ($path = $this->findImport($path)) {
				$this->importFile($path, $out);

				return TRUE;
			}

			return FALSE;
		}
		if ($rawPath[0] == 'list') {
			// handle a list of strings
			if (count($rawPath[2]) == 0) return FALSE;
			foreach ($rawPath[2] as $path) {
				if ($path[0] != 'string') return FALSE;
			}
			foreach ($rawPath[2] as $path) {
				$this->compileImport($path, $out);
			}

			return TRUE;
		}

		return FALSE;
	}

	protected function compileStringContent($string) {
		$parts = [];
		foreach ($string[2] as $part) {
			if (is_array($part)) {
				$parts[] = $this->compileValue($part);
			} else {
				$parts[] = $part;
			}
		}

		return implode($parts);
	}

	/**
	 * Compiles a primitive value into a CSS property value.
	 *
	 * Values in scssphp are typed by being wrapped in arrays, their format is
	 * typically:
	 *
	 *     array(type, contents [, additional_contents]*)
	 *
	 * The input is expected to be reduced. This function will not work on
	 * things like expressions and variables.
	 *
	 * @param array $value
	 */
	protected function compileValue($value) {
		$value = $this->reduce($value);
		list($type) = $value;
		switch ($type) {
			case 'keyword':
				return $value[1];
			case 'color':
				// [1] - red component (either number for a %)
				// [2] - green component
				// [3] - blue component
				// [4] - optional alpha component
				list(, $r, $g, $b) = $value;
				$r = round($r);
				$g = round($g);
				$b = round($b);
				if (count($value) == 5 && $value[4] != 1) { // rgba
					return 'rgba(' . $r . ', ' . $g . ', ' . $b . ', ' . $value[4] . ')';
				}
				$h = sprintf('#%02x%02x%02x', $r, $g, $b);
				// Converting hex color to short notation (e.g. #003399 to #039)
				if ($h[1] == $h[2] && $h[3] == $h[4] && $h[5] == $h[6]) {
					$h = '#' . $h[1] . $h[3] . $h[5];
				}

				return $h;
			case 'number':
				return round($value[1], $this->numberPrecision) . $value[2];
			case 'string':
				return $value[1] . $this->compileStringContent($value) . $value[1];
			case 'function':
				$args = !empty($value[2]) ? $this->compileValue($value[2]) : '';

				return "$value[1]($args)";
			case 'list':
				$value = $this->extractInterpolation($value);
				if ($value[0] != 'list') return $this->compileValue($value);
				list(, $delim, $items) = $value;
				$filtered = [];
				foreach ($items as $item) {
					if ($item[0] == 'null') continue;
					$filtered[] = $this->compileValue($item);
				}

				return implode("$delim ", $filtered);
			case 'interpolated': # node created by extractInterpolation
				list(, $interpolate, $left, $right) = $value;
				list(, , $whiteLeft, $whiteRight) = $interpolate;
				$left = count($left[2]) > 0 ?
					$this->compileValue($left) . $whiteLeft : '';
				$right = count($right[2]) > 0 ?
					$whiteRight . $this->compileValue($right) : '';

				return $left . $this->compileValue($interpolate) . $right;
			case 'interpolate': # raw parse node
				list(, $exp) = $value;
				// strip quotes if it's a string
				$reduced = $this->reduce($exp);
				switch ($reduced[0]) {
					case 'string':
						$reduced = ['keyword',
						            $this->compileStringContent($reduced)];
						break;
					case 'null':
						$reduced = ['keyword', ''];
				}

				return $this->compileValue($reduced);
			case 'null':
				return 'null';
			default:
				$this->throwError("unknown value type: $type");
		}
	}

	protected function extractInterpolation($list) {
		$items = $list[2];
		foreach ($items as $i => $item) {
			if ($item[0] == 'interpolate') {
				$before = ['list', $list[1], array_slice($items, 0, $i)];
				$after  = ['list', $list[1], array_slice($items, $i + 1)];

				return ['interpolated', $item, $before, $after];
			}
		}

		return $list;
	}

	public function findImport($url) {
		$urls = [];
		// for "normal" scss imports (ignore vanilla css and external requests)
		if (!preg_match('/\.css|^http:\/\/$/', $url)) {
			// try both normal and the _partial filename
			$urls = [$url, preg_replace('/[^\/]+$/', '_\0', $url)];
		}
		foreach ($this->importPaths as $dir) {
			if (is_string($dir)) {
				// check urls for normal import paths
				foreach ($urls as $full) {
					$full = $dir .
					        (!empty($dir) && substr($dir, -1) != '/' ? '/' : '') .
					        $full;
					if ($this->fileExists($file = $full . '.scss') ||
					    $this->fileExists($file = $full)
					) {
						return $file;
					}
				}
			} else {
				// check custom callback for import path
				$file = call_user_func($dir, $url, $this);
				if ($file != NULL) {
					return $file;
				}
			}
		}

		return NULL;
	}

	// results the file path for an import url if it exists
	protected function fileExists($name) {
		return is_file($name);
	}

	protected function importFile($path, $out) {
		// see if tree is cached
		$realPath = realpath($path);
		if (isset($this->importCache[ $realPath ])) {
			$tree = $this->importCache[ $realPath ];
		} else {
			$code                = file_get_contents($path);
			$parser              = new titanscss_parser($path, FALSE);
			$tree                = $parser->parse($code);
			$this->parsedFiles[] = $path;
			$this->importCache[ $realPath ] = $tree;
		}
		$pi = pathinfo($path);
		array_unshift($this->importPaths, $pi['dirname']);
		$this->compileChildren($tree->children, $out);
		array_shift($this->importPaths);
	}

	protected function compileNestedBlock($block, $selectors) {
		$this->pushEnv($block);
		$this->scope                     = $this->makeOutputBlock($block->type, $selectors);
		$this->scope->parent->children[] = $this->scope;
		$this->compileChildren($block->children, $this->scope);
		$this->scope = $this->scope->parent;
		$this->popEnv();
	}

	// sorts any keyword arguments
	// TODO: merge with apply arguments
	protected function compileMedia($media) {
		$this->pushEnv($media);
		$mediaQuery = $this->compileMediaQuery($this->multiplyMedia($this->env));
		if (!empty($mediaQuery)) {
			$this->scope = $this->makeOutputBlock('media', [$mediaQuery]);
			$parentScope = $this->mediaParent($this->scope);
			$parentScope->children[] = $this->scope;
			// top level properties in a media cause it to be wrapped
			$needsWrap = FALSE;
			foreach ($media->children as $child) {
				$type = $child[0];
				if ($type != 'block' && $type != 'media' && $type != 'directive') {
					$needsWrap = TRUE;
					break;
				}
			}
			if ($needsWrap) {
				$wrapped         = (object)[
					'selectors' => [],
					'children'  => $media->children
				];
				$media->children = [['block', $wrapped]];
			}
			$this->compileChildren($media->children, $this->scope);
			$this->scope = $this->scope->parent;
		}
		$this->popEnv();
	}

	protected function compileMediaQuery($queryList) {
		$out   = '@media';
		$first = TRUE;
		foreach ($queryList as $query) {
			$type  = NULL;
			$parts = [];
			foreach ($query as $q) {
				switch ($q[0]) {
					case 'mediaType':
						if ($type) {
							$type = $this->mergeMediaTypes($type,
							                               array_map([$this, 'compileValue'], array_slice($q, 1)));
							if (empty($type)) { // merge failed
								return NULL;
							}
						} else {
							$type = array_map([$this, 'compileValue'], array_slice($q, 1));
						}
						break;
					case 'mediaExp':
						if (isset($q[2])) {
							$parts[]
								= '(' . $this->compileValue($q[1]) . $this->formatter->assignSeparator . $this->compileValue($q[2]) . ')';
						} else {
							$parts[] = '(' . $this->compileValue($q[1]) . ')';
						}
						break;
				}
			}
			if ($type) {
				array_unshift($parts, implode(' ', array_filter($type)));
			}
			if (!empty($parts)) {
				if ($first) {
					$first = FALSE;
					$out .= ' ';
				} else {
					$out .= $this->formatter->tagSeparator;
				}
				$out .= implode(' and ', $parts);
			}
		}

		return $out;
	}

	protected function mergeMediaTypes($type1, $type2) {
		if (empty($type1)) {
			return $type2;
		}
		if (empty($type2)) {
			return $type1;
		}
		$m1 = '';
		$t1 = '';
		if (count($type1) > 1) {
			$m1 = strtolower($type1[0]);
			$t1 = strtolower($type1[1]);
		} else {
			$t1 = strtolower($type1[0]);
		}
		$m2 = '';
		$t2 = '';
		if (count($type2) > 1) {
			$m2 = strtolower($type2[0]);
			$t2 = strtolower($type2[1]);
		} else {
			$t2 = strtolower($type2[0]);
		}
		if (($m1 == 'not') ^ ($m2 == 'not')) {
			if ($t1 == $t2) {
				return NULL;
			}

			return [
				$m1 == 'not' ? $m2 : $m1,
				$m1 == 'not' ? $t2 : $t1
			];
		} elseif ($m1 == 'not' && $m2 == 'not') {
			# CSS has no way of representing "neither screen nor print"
			if ($t1 != $t2) {
				return NULL;
			}

			return ['not', $t1];
		} elseif ($t1 != $t2) {
			return NULL;
		} else { // t1 == t2, neither m1 nor m2 are "not"
			return [empty($m1) ? $m2 : $m1, $t1];
		}
	}

	protected function multiplyMedia($env, $childQueries = NULL) {
		if (!isset($env) ||
		    !empty($env->block->type) && $env->block->type != 'media'
		) {
			return $childQueries;
		}
		// plain old block, skip
		if (empty($env->block->type)) {
			return $this->multiplyMedia($env->parent, $childQueries);
		}
		$parentQueries = $env->block->queryList;
		if ($childQueries == NULL) {
			$childQueries = $parentQueries;
		} else {
			$originalQueries = $childQueries;
			$childQueries    = [];
			foreach ($parentQueries as $parentQuery) {
				foreach ($originalQueries as $childQuery) {
					$childQueries [] = array_merge($parentQuery, $childQuery);
				}
			}
		}

		return $this->multiplyMedia($env->parent, $childQueries);
	}

	protected function mediaParent($scope) {
		while (!empty($scope->parent)) {
			if (!empty($scope->type) && $scope->type != 'media') {
				break;
			}
			$scope = $scope->parent;
		}

		return $scope;
	}

	/**
	 * Recursively compiles a block.
	 *
	 * A block is analogous to a CSS block in most cases. A single SCSS document
	 * is encapsulated in a block when parsed, but it does not have parent tags
	 * so all of its children appear on the root level when compiled.
	 *
	 * Blocks are made up of selectors and children.
	 *
	 * The children of a block are just all the blocks that are defined within.
	 *
	 * Compiling the block involves pushing a fresh environment on the stack,
	 * and iterating through the props, compiling each one.
	 *
	 * @see scss::compileChild()
	 *
	 * @param \StdClass $block
	 */
	protected function compileBlock($block) {
		$env = $this->pushEnv($block);
		$env->selectors = array_map([$this, 'evalSelector'], $block->selectors);
		$out                     = $this->makeOutputBlock(NULL, $this->multiplySelectors($env));
		$this->scope->children[] = $out;
		$this->compileChildren($block->children, $out);
		$this->popEnv();
	}

	protected function multiplySelectors($env) {
		$envs = [];
		while (NULL != $env) {
			if (!empty($env->selectors)) {
				$envs[] = $env;
			}
			$env = $env->parent;
		};
		$selectors       = [];
		$parentSelectors = [[]];
		while ($env = array_pop($envs)) {
			$selectors = [];
			foreach ($env->selectors as $selector) {
				foreach ($parentSelectors as $parent) {
					$selectors[] = $this->joinSelectors($parent, $selector);
				}
			}
			$parentSelectors = $selectors;
		}

		return $selectors;
	}

	protected function joinSelectors($parent, $child) {
		$setSelf = FALSE;
		$out     = [];
		foreach ($child as $part) {
			$newPart = [];
			foreach ($part as $p) {
				if ($p == self::$selfSelector) {
					$setSelf = TRUE;
					foreach ($parent as $i => $parentPart) {
						if ($i > 0) {
							$out[]   = $newPart;
							$newPart = [];
						}
						foreach ($parentPart as $pp) {
							$newPart[] = $pp;
						}
					}
				} else {
					$newPart[] = $p;
				}
			}
			$out[] = $newPart;
		}

		return $setSelf ? $out : array_merge($parent, $child);
	}

	// make sure a color's components don't go out of bounds
	protected function compileComment($block) {
		$out                     = $this->makeOutputBlock('comment');
		$out->lines[]            = $block[1];
		$this->scope->children[] = $out;
	}

	protected function evalSelector($selector) {
		return array_map([$this, 'evalSelectorPart'], $selector);
	}

	protected function pushExtends($target, $origin) {
		if ($this->isSelfExtend($target, $origin)) {
			return;
		}
		$i               = count($this->extends);
		$this->extends[] = [$target, $origin];
		foreach ($target as $part) {
			if (isset($this->extendsMap[ $part ])) {
				$this->extendsMap[ $part ][] = $i;
			} else {
				$this->extendsMap[ $part ] = [$i];
			}
		}
	}

	// H from 0 to 360, S and L from 0 to 100
	protected function isSelfExtend($target, $origin) {
		foreach ($origin as $sel) {
			if (in_array($target, $sel)) {
				return TRUE;
			}
		}

		return FALSE;
	}

	// Built in functions
	protected function isTruthy($value) {
		return $value != self::$false && $value != self::$null;
	}

	protected function coerceList($item, $delim = ',') {
		if (isset($item) && $item[0] == 'list') {
			return $item;
		}

		return ['list', $delim, !isset($item) ? [] : [$item]];
	}

	protected function flattenSelectors($block, $parentKey = NULL) {
		if ($block->selectors) {
			$selectors = [];
			foreach ($block->selectors as $s) {
				$selectors[] = $s;
				if (!is_array($s)) continue;
				// check extends
				if (!empty($this->extendsMap)) {
					$this->matchExtends($s, $selectors);
				}
			}
			$block->selectors    = [];
			$placeholderSelector = FALSE;
			foreach ($selectors as $selector) {
				if ($this->hasSelectorPlaceholder($selector)) {
					$placeholderSelector = TRUE;
					continue;
				}
				$block->selectors[] = $this->compileSelector($selector);
			}
			if ($placeholderSelector && 0 == count($block->selectors) && NULL != $parentKey) {
				unset($block->parent->children[ $parentKey ]);

				return;
			}
		}
		foreach ($block->children as $key => $child) {
			$this->flattenSelectors($child, $key);
		}
	}

	protected function matchExtends($selector, &$out, $from = 0, $initial = TRUE) {
		foreach ($selector as $i => $part) {
			if ($i < $from) continue;
			if ($this->matchExtendsSingle($part, $origin)) {
				$before = array_slice($selector, 0, $i);
				$after  = array_slice($selector, $i + 1);
				foreach ($origin as $new) {
					$k = 0;
					// remove shared parts
					if ($initial) {
						foreach ($before as $k => $val) {
							if (!isset($new[ $k ]) || $val != $new[ $k ]) {
								break;
							}
						}
					}
					$result = array_merge(
						$before,
						$k > 0 ? array_slice($new, $k) : $new,
						$after);
					if ($result == $selector) continue;
					$out[] = $result;
					// recursively check for more matches
					$this->matchExtends($result, $out, $i, FALSE);
					// selector sequence merging
					if (!empty($before) && count($new) > 1) {
						$result2 = array_merge(
							array_slice($new, 0, -1),
							$k > 0 ? array_slice($before, $k) : $before,
							array_slice($new, -1),
							$after);
						$out[] = $result2;
					}
				}
			}
		}
	}

	protected function matchExtendsSingle($single, &$outOrigin) {
		$counts = [];
		foreach ($single as $part) {
			if (!is_string($part)) return FALSE; // hmm
			if (isset($this->extendsMap[ $part ])) {
				foreach ($this->extendsMap[ $part ] as $idx) {
					$counts[ $idx ] = isset($counts[ $idx ]) ? $counts[ $idx ] + 1 : 1;
				}
			}
		}
		$outOrigin = [];
		$found     = FALSE;
		foreach ($counts as $idx => $count) {
			list($target, $origin) = $this->extends[ $idx ];
			// check count
			if ($count != count($target)) continue;
			// check if target is subset of single
			if (array_diff(array_intersect($single, $target), $target)) continue;
			$rem = array_diff($single, $target);
			foreach ($origin as $j => $new) {
				// prevent infinite loop when target extends itself
				foreach ($new as $new_selector) {
					if (!array_diff($single, $new_selector)) {
						continue 2;
					}
				}
				$origin[ $j ][ count($origin[ $j ]) - 1 ] = $this->combineSelectorSingle(end($new), $rem);
			}
			$outOrigin = array_merge($outOrigin, $origin);
			$found = TRUE;
		}

		return $found;
	}

	protected function combineSelectorSingle($base, $other) {
		$tag = NULL;
		$out = [];
		foreach ([$base, $other] as $single) {
			foreach ($single as $part) {
				if (preg_match('/^[^\[.#:]/', $part)) {
					$tag = $part;
				} else {
					$out[] = $part;
				}
			}
		}
		if ($tag) {
			array_unshift($out, $tag);
		}

		return $out;
	}

	protected function hasSelectorPlaceholder($selector) {
		if (!is_array($selector)) return FALSE;
		foreach ($selector as $parts) {
			foreach ($parts as $part) {
				if ('%' == $part[0]) {
					return TRUE;
				}
			}
		}

		return FALSE;
	}

	protected function compileSelector($selector) {
		if (!is_array($selector)) return $selector; // media and the like
		return implode(' ', array_map(
			[$this, 'compileSelectorPart'], $selector));
	}

	// helper function for adjust_color, change_color, and scale_color
	/**
	 * Set variables
	 *
	 * @param array $variables
	 */
	public function setVariables(array $variables) {
		$this->registeredVars = array_merge($this->registeredVars, $variables);
	}

	/**
	 * Unset variable
	 *
	 * @param string $name
	 */
	public function unsetVariable($name) {
		unset($this->registeredVars[ $name ]);
	}

	public function getParsedFiles() {
		return $this->parsedFiles;
	}

	public function addImportPath($path) {
		$this->importPaths[] = $path;
	}

	public function setImportPaths($path) {
		$this->importPaths = (array)$path;
	}

	public function setNumberPrecision($numberPrecision) {
		$this->numberPrecision = $numberPrecision;
	}

	public function setFormatter($formatterName) {
		$this->formatter = $formatterName;
	}

	public function registerFunction($name, $func) {
		$this->userFunctions[ $this->normalizeName($name) ] = $func;
	}

	public function unregisterFunction($name) {
		unset($this->userFunctions[ $this->normalizeName($name) ]);
	}

	protected function evalSelectorPart($piece) {
		foreach ($piece as &$p) {
			if (!is_array($p)) continue;
			switch ($p[0]) {
				case 'interpolate':
					$p = $this->compileValue($p);
					break;
				case 'string':
					$p = $this->compileValue($p);
					break;
			}
		}

		return $this->flattenSelectorSingle($piece);
	}

	protected function flattenSelectorSingle($single) {
		$joined = [];
		foreach ($single as $part) {
			if (empty($joined) ||
			    !is_string($part) ||
			    preg_match('/[\[.:#%]/', $part)
			) {
				$joined[] = $part;
				continue;
			}
			if (is_array(end($joined))) {
				$joined[] = $part;
			} else {
				$joined[ count($joined) - 1 ] .= $part;
			}
		}

		return $joined;
	}

	protected function compileSelectorPart($piece) {
		foreach ($piece as &$p) {
			if (!is_array($p)) continue;
			switch ($p[0]) {
				case 'self':
					$p = '&';
					break;
				default:
					$p = $this->compileValue($p);
					break;
			}
		}

		return implode($piece);
	}

	protected function op_add_number_number($left, $right) {
		return ['number', $left[1] + $right[1], $left[2]];
	}

	protected function op_mul_number_number($left, $right) {
		return ['number', $left[1] * $right[1], $left[2]];
	}

	protected function op_sub_number_number($left, $right) {
		return ['number', $left[1] - $right[1], $left[2]];
	}

	protected function op_div_number_number($left, $right) {
		return ['number', $left[1] / $right[1], $left[2]];
	}

	protected function op_mod_number_number($left, $right) {
		return ['number', $left[1] % $right[1], $left[2]];
	}

	protected function op_add($left, $right) {
		if ($strLeft = $this->coerceString($left)) {
			if ($right[0] == 'string') {
				$right[1] = '';
			}
			$strLeft[2][] = $right;

			return $strLeft;
		}
		if ($strRight = $this->coerceString($right)) {
			if ($left[0] == 'string') {
				$left[1] = '';
			}
			array_unshift($strRight[2], $left);

			return $strRight;
		}
	}

	protected function coerceString($value) {
		switch ($value[0]) {
			case 'string':
				return $value;
			case 'keyword':
				return ['string', '', [$value[1]]];
		}

		return NULL;
	}

	protected function op_and($left, $right, $shouldEval) {
		if (!$shouldEval) return;
		if ($left != self::$false) return $right;

		return $left;
	}

	protected function op_or($left, $right, $shouldEval) {
		if (!$shouldEval) return;
		if ($left != self::$false) return $left;

		return $right;
	}

	protected function op_color_number($op, $left, $right) {
		$value = $right[1];

		return $this->op_color_color($op, $left,
		                             ['color', $value, $value, $value]);
	}

	// mix two colors
	protected function op_color_color($op, $left, $right) {
		$out = ['color'];
		foreach (range(1, 3) as $i) {
			$lval = isset($left[ $i ]) ? $left[ $i ] : 0;
			$rval = isset($right[ $i ]) ? $right[ $i ] : 0;
			switch ($op) {
				case '+':
					$out[] = $lval + $rval;
					break;
				case '-':
					$out[] = $lval - $rval;
					break;
				case '*':
					$out[] = $lval * $rval;
					break;
				case '%':
					$out[] = $lval % $rval;
					break;
				case '/':
					if ($rval == 0) {
						$this->throwError("color: Can't divide by zero");
					}
					$out[] = $lval / $rval;
					break;
				case '==':
					return $this->op_eq($left, $right);
				case '!=':
					return $this->op_neq($left, $right);
				default:
					$this->throwError("color: unknown op $op");
			}
		}
		if (isset($left[4])) {
			$out[4] = $left[4];
		} elseif (isset($right[4])) $out[4] = $right[4];

		return $this->fixColor($out);
	}

	protected function op_eq($left, $right) {
		if (($lStr = $this->coerceString($left)) && ($rStr = $this->coerceString($right))) {
			$lStr[1] = '';
			$rStr[1] = '';

			return $this->toBool($this->compileValue($lStr) == $this->compileValue($rStr));
		}

		return $this->toBool($left == $right);
	}

	public function toBool($thing) {
		return $thing ? self::$true : self::$false;
	}

	protected function op_neq($left, $right) {
		return $this->toBool($left != $right);
	}

	protected function fixColor($c) {
		foreach (range(1, 3) as $i) {
			if ($c[ $i ] < 0) $c[ $i ] = 0;
			if ($c[ $i ] > 255) $c[ $i ] = 255;
		}

		return $c;
	}

	protected function op_number_color($op, $left, $right) {
		$value = $left[1];

		return $this->op_color_color($op,
		                             ['color', $value, $value, $value], $right);
	}

	protected function op_gte_number_number($left, $right) {
		return $this->toBool($left[1] >= $right[1]);
	}

	protected function op_gt_number_number($left, $right) {
		return $this->toBool($left[1] > $right[1]);
	}

	protected function op_lte_number_number($left, $right) {
		return $this->toBool($left[1] <= $right[1]);
	}

	protected function op_lt_number_number($left, $right) {
		return $this->toBool($left[1] < $right[1]);
	}

	protected function lib_if($args) {
		list($cond, $t, $f) = $args;
		if (!$this->isTruthy($cond)) return $f;

		return $t;
	}

	protected function lib_index($args) {
		list($list, $value) = $args;
		$list = $this->assertList($list);
		$values = [];
		foreach ($list[2] as $item) {
			$values[] = $this->normalizeValue($item);
		}
		$key = array_search($this->normalizeValue($value), $values);

		return FALSE == $key ? FALSE : $key + 1;
	}

	public function assertList($value) {
		if ($value[0] != 'list') {
			$this->throwError('expecting list');
		}

		return $value;
	}

	public function normalizeValue($value) {
		$value = $this->coerceForExpression($this->reduce($value));
		list($type) = $value;
		switch ($type) {
			case 'list':
				$value = $this->extractInterpolation($value);
				if ($value[0] != 'list') {
					return ['keyword', $this->compileValue($value)];
				}
				foreach ($value[2] as $key => $item) {
					$value[2][ $key ] = $this->normalizeValue($item);
				}

				return $value;
			case 'number':
				return $this->normalizeNumber($value);
			default:
				return $value;
		}
	}

	protected function lib_rgb($args) {
		list($r, $g, $b) = $args;

		return ['color', $r[1], $g[1], $b[1]];
	}

	protected function lib_rgba($args) {
		if ($color = $this->coerceColor($args[0])) {
			$num      = !isset($args[1]) ? $args[3] : $args[1];
			$alpha    = $this->assertNumber($num);
			$color[4] = $alpha;

			return $color;
		}
		list($r, $g, $b, $a) = $args;

		return ['color', $r[1], $g[1], $b[1], $a[1]];
	}

	public function assertNumber($value) {
		if ($value[0] != 'number') {
			$this->throwError('expecting number');
		}

		return $value[1];
	}

	protected function adjust_color_helper($base, $alter, $i) {
		return $base += $alter;
	}

	protected function lib_adjust_color($args) {
		return $this->alter_color($args, 'adjust_color_helper');
	}

	protected function alter_color($args, $fn) {
		$color = $this->assertColor($args[0]);
		foreach ([1, 2, 3, 7] as $i) {
			if (isset($args[ $i ])) {
				$val          = $this->assertNumber($args[ $i ]);
				$ii           = $i == 7 ? 4 : $i; // alpha
				$color[ $ii ] = $this->$fn(isset($color[ $ii ]) ? $color[ $ii ] : 0, $val, $i);
			}
		}
		if (isset($args[4]) || isset($args[5]) || isset($args[6])) {
			$hsl = $this->toHSL($color[1], $color[2], $color[3]);
			foreach ([4, 5, 6] as $i) {
				if (isset($args[ $i ])) {
					$val           = $this->assertNumber($args[ $i ]);
					$hsl[ $i - 3 ] = $this->$fn($hsl[ $i - 3 ], $val, $i);
				}
			}
			$rgb = $this->toRGB($hsl[1], $hsl[2], $hsl[3]);
			if (isset($color[4])) $rgb[4] = $color[4];
			$color = $rgb;
		}

		return $color;
	}

	public function assertColor($value) {
		if ($color = $this->coerceColor($value)) return $color;
		$this->throwError('expecting color');
	}

	public function toHSL($red, $green, $blue) {
		$min = min($red, $green, $blue);
		$max = max($red, $green, $blue);
		$l = $min + $max;
		if ($min == $max) {
			$s = $h = 0;
		} else {
			$d = $max - $min;
			if ($l < 255) {
				$s = $d / $l;
			} else {
				$s = $d / (510 - $l);
			}
			if ($red == $max) {
				$h = 60 * ($green - $blue) / $d;
			} elseif ($green == $max) {
				$h = 60 * ($blue - $red) / $d + 120;
			} elseif ($blue == $max) {
				$h = 60 * ($red - $green) / $d + 240;
			}
		}

		return ['hsl', fmod($h, 360), $s * 100, $l / 5.1];
	}

	public function toRGB($hue, $saturation, $lightness) {
		if ($hue < 0) {
			$hue += 360;
		}
		$h = $hue / 360;
		$s = min(100, max(0, $saturation)) / 100;
		$l = min(100, max(0, $lightness)) / 100;
		$m2 = $l <= 0.5 ? $l * ($s + 1) : $l + $s - $l * $s;
		$m1 = $l * 2 - $m2;
		$r = $this->hueToRGB($m1, $m2, $h + 1 / 3) * 255;
		$g = $this->hueToRGB($m1, $m2, $h) * 255;
		$b = $this->hueToRGB($m1, $m2, $h - 1 / 3) * 255;
		$out = ['color', $r, $g, $b];

		return $out;
	}

	public function hueToRGB($m1, $m2, $h) {
		if ($h < 0) {
			$h += 1;
		} elseif ($h > 1) {
			$h -= 1;
		}
		if ($h * 6 < 1) {
			return $m1 + ($m2 - $m1) * $h * 6;
		}
		if ($h * 2 < 1) {
			return $m2;
		}
		if ($h * 3 < 2) {
			return $m1 + ($m2 - $m1) * (2 / 3 - $h) * 6;
		}

		return $m1;
	}

	protected function change_color_helper($base, $alter, $i) {
		return $alter;
	}

	protected function lib_change_color($args) {
		return $this->alter_color($args, 'change_color_helper');
	}

	protected function scale_color_helper($base, $scale, $i) {
		// 1,2,3 - rgb
		// 4, 5, 6 - hsl
		// 7 - a
		switch ($i) {
			case 1:
			case 2:
			case 3:
				$max = 255;
				break;
			case 4:
				$max = 360;
				break;
			case 7:
				$max = 1;
				break;
			default:
				$max = 100;
		}
		$scale = $scale / 100;
		if ($scale < 0) {
			return $base * $scale + $base;
		} else {
			return ($max - $base) * $scale + $base;
		}
	}

	protected function lib_scale_color($args) {
		return $this->alter_color($args, 'scale_color_helper');
	}

	protected function lib_ie_hex_str($args) {
		$color    = $this->coerceColor($args[0]);
		$color[4] = isset($color[4]) ? round(255 * $color[4]) : 255;

		return sprintf('#%02X%02X%02X%02X', $color[4], $color[1], $color[2], $color[3]);
	}

	// increases opacity by amount
	protected function lib_red($args) {
		$color = $this->coerceColor($args[0]);

		return $color[1];
	}

	protected function lib_green($args) {
		$color = $this->coerceColor($args[0]);

		return $color[2];
	}

	protected function lib_blue($args) {
		$color = $this->coerceColor($args[0]);

		return $color[3];
	}

	protected function lib_opacity($args) {
		$value = $args[0];
		if ($value[0] == 'number') return NULL;

		return $this->lib_alpha($args);
	}

	// decreases opacity by amount
	protected function lib_alpha($args) {
		if ($color = $this->coerceColor($args[0])) {
			return isset($color[4]) ? $color[4] : 1;
		}

		// this might be the IE function, so return value unchanged
		return NULL;
	}

	protected function lib_mix($args) {
		list($first, $second, $weight) = $args;
		$first  = $this->assertColor($first);
		$second = $this->assertColor($second);
		if (!isset($weight)) {
			$weight = 0.5;
		} else {
			$weight = $this->coercePercent($weight);
		}
		$firstAlpha  = isset($first[4]) ? $first[4] : 1;
		$secondAlpha = isset($second[4]) ? $second[4] : 1;
		$w = $weight * 2 - 1;
		$a = $firstAlpha - $secondAlpha;
		$w1 = (($w * $a == -1 ? $w : ($w + $a) / (1 + $w * $a)) + 1) / 2.0;
		$w2 = 1.0 - $w1;
		$new = ['color',
		        $w1 * $first[1] + $w2 * $second[1],
		        $w1 * $first[2] + $w2 * $second[2],
		        $w1 * $first[3] + $w2 * $second[3],
		];
		if ($firstAlpha != 1.0 || $secondAlpha != 1.0) {
			$new[] = $firstAlpha * $weight + $secondAlpha * ($weight - 1);
		}

		return $this->fixColor($new);
	}

	protected function coercePercent($value) {
		if ($value[0] == 'number') {
			if ($value[2] == '%') {
				return $value[1] / 100;
			}

			return $value[1];
		}

		return 0;
	}

	protected function lib_hsl($args) {
		list($h, $s, $l) = $args;

		return $this->toRGB($h[1], $s[1], $l[1]);
	}

	protected function lib_hsla($args) {
		list($h, $s, $l, $a) = $args;
		$color    = $this->toRGB($h[1], $s[1], $l[1]);
		$color[4] = $a[1];

		return $color;
	}

	protected function lib_hue($args) {
		$color = $this->assertColor($args[0]);
		$hsl   = $this->toHSL($color[1], $color[2], $color[3]);

		return ['number', $hsl[1], 'deg'];
	}

	protected function lib_saturation($args) {
		$color = $this->assertColor($args[0]);
		$hsl   = $this->toHSL($color[1], $color[2], $color[3]);

		return ['number', $hsl[2], '%'];
	}

	protected function lib_lightness($args) {
		$color = $this->assertColor($args[0]);
		$hsl   = $this->toHSL($color[1], $color[2], $color[3]);

		return ['number', $hsl[3], '%'];
	}

	protected function lib_adjust_hue($args) {
		$color   = $this->assertColor($args[0]);
		$degrees = $this->assertNumber($args[1]);

		return $this->adjustHsl($color, 1, $degrees);
	}

	protected function adjustHsl($color, $idx, $amount) {
		$hsl = $this->toHSL($color[1], $color[2], $color[3]);
		$hsl[ $idx ] += $amount;
		$out = $this->toRGB($hsl[1], $hsl[2], $hsl[3]);
		if (isset($color[4])) $out[4] = $color[4];

		return $out;
	}

	protected function lib_lighten($args) {
		$color  = $this->assertColor($args[0]);
		$amount = 100 * $this->coercePercent($args[1]);

		return $this->adjustHsl($color, 3, $amount);
	}

	protected function lib_darken($args) {
		$color  = $this->assertColor($args[0]);
		$amount = 100 * $this->coercePercent($args[1]);

		return $this->adjustHsl($color, 3, -$amount);
	}

	protected function lib_saturate($args) {
		$value = $args[0];
		if ($value[0] == 'number') return NULL;
		$color  = $this->assertColor($value);
		$amount = 100 * $this->coercePercent($args[1]);

		return $this->adjustHsl($color, 2, $amount);
	}

	protected function lib_desaturate($args) {
		$color  = $this->assertColor($args[0]);
		$amount = 100 * $this->coercePercent($args[1]);

		return $this->adjustHsl($color, 2, -$amount);
	}

	protected function lib_grayscale($args) {
		$value = $args[0];
		if ($value[0] == 'number') return NULL;

		return $this->adjustHsl($this->assertColor($value), 2, -100);
	}

	protected function lib_complement($args) {
		return $this->adjustHsl($this->assertColor($args[0]), 1, 180);
	}

	protected function lib_invert($args) {
		$value = $args[0];
		if ($value[0] == 'number') return NULL;
		$color    = $this->assertColor($value);
		$color[1] = 255 - $color[1];
		$color[2] = 255 - $color[2];
		$color[3] = 255 - $color[3];

		return $color;
	}

	protected function lib_fade_in($args) {
		return $this->lib_opacify($args);
	}

	protected function lib_opacify($args) {
		$color  = $this->assertColor($args[0]);
		$amount = $this->coercePercent($args[1]);
		$color[4] = (isset($color[4]) ? $color[4] : 1) + $amount;
		$color[4] = min(1, max(0, $color[4]));

		return $color;
	}

	protected function lib_fade_out($args) {
		return $this->lib_transparentize($args);
	}

	protected function lib_transparentize($args) {
		$color  = $this->assertColor($args[0]);
		$amount = $this->coercePercent($args[1]);
		$color[4] = (isset($color[4]) ? $color[4] : 1) - $amount;
		$color[4] = min(1, max(0, $color[4]));

		return $color;
	}

	protected function lib_unquote($args) {
		$str = $args[0];
		if ($str[0] == 'string') $str[1] = '';

		return $str;
	}

	protected function lib_quote($args) {
		$value = $args[0];
		if ($value[0] == 'string' && !empty($value[1])) {
			return $value;
		}

		return ['string', '"', [$value]];
	}

	protected function lib_percentage($args) {
		return ['number',
		        $this->coercePercent($args[0]) * 100,
		        '%'];
	}

	protected function lib_round($args) {
		$num    = $args[0];
		$num[1] = round($num[1]);

		return $num;
	}

	protected function lib_floor($args) {
		$num    = $args[0];
		$num[1] = floor($num[1]);

		return $num;
	}

	protected function lib_ceil($args) {
		$num    = $args[0];
		$num[1] = ceil($num[1]);

		return $num;
	}

	protected function lib_abs($args) {
		$num    = $args[0];
		$num[1] = abs($num[1]);

		return $num;
	}

	protected function lib_min($args) {
		$numbers = $this->getNormalizedNumbers($args);
		$min     = NULL;
		foreach ($numbers as $key => $number) {
			if (NULL == $min || $number[1] <= $min[1]) {
				$min = [$key, $number[1]];
			}
		}

		return $args[ $min[0] ];
	}

	protected function getNormalizedNumbers($args) {
		$unit         = NULL;
		$originalUnit = NULL;
		$numbers      = [];
		foreach ($args as $key => $item) {
			if ('number' != $item[0]) {
				$this->throwError('%s is not a number', $item[0]);
			}
			$number = $this->normalizeNumber($item);
			if (NULL == $unit) {
				$unit         = $number[2];
				$originalUnit = $item[2];
			} elseif ($unit != $number[2]) {
				$this->throwError('Incompatible units: "%s" and "%s".', $originalUnit, $item[2]);
			}
			$numbers[ $key ] = $number;
		}

		return $numbers;
	}

	protected function lib_max($args) {
		$numbers = $this->getNormalizedNumbers($args);
		$max     = NULL;
		foreach ($numbers as $key => $number) {
			if (NULL == $max || $number[1] >= $max[1]) {
				$max = [$key, $number[1]];
			}
		}

		return $args[ $max[0] ];
	}

	protected function lib_length($args) {
		$list = $this->coerceList($args[0]);

		return count($list[2]);
	}

	protected function lib_nth($args) {
		$list = $this->coerceList($args[0]);
		$n    = $this->assertNumber($args[1]) - 1;

		return isset($list[2][ $n ]) ? $list[2][ $n ] : self::$defaultValue;
	}

	protected function lib_join($args) {
		list($list1, $list2, $sep) = $args;
		$list1 = $this->coerceList($list1, ' ');
		$list2 = $this->coerceList($list2, ' ');
		$sep   = $this->listSeparatorForJoin($list1, $sep);

		return ['list', $sep, array_merge($list1[2], $list2[2])];
	}

	protected function listSeparatorForJoin($list1, $sep) {
		if (!isset($sep)) return $list1[1];
		switch ($this->compileValue($sep)) {
			case 'comma':
				return ',';
			case 'space':
				return '';
			default:
				return $list1[1];
		}
	}

	protected function lib_append($args) {
		list($list1, $value, $sep) = $args;
		$list1 = $this->coerceList($list1, ' ');
		$sep   = $this->listSeparatorForJoin($list1, $sep);

		return ['list', $sep, array_merge($list1[2], [$value])];
	}

	protected function lib_zip($args) {
		foreach ($args as $arg) {
			$this->assertList($arg);
		}
		$lists     = [];
		$firstList = array_shift($args);
		foreach ($firstList[2] as $key => $item) {
			$list = ['list', '', [$item]];
			foreach ($args as $arg) {
				if (isset($arg[2][ $key ])) {
					$list[2][] = $arg[2][ $key ];
				} else {
					break 2;
				}
			}
			$lists[] = $list;
		}

		return ['list', ',', $lists];
	}

	protected function lib_type_of($args) {
		$value = $args[0];
		switch ($value[0]) {
			case 'keyword':
				if ($value == self::$true || $value == self::$false) {
					return 'bool';
				}
				if ($this->coerceColor($value)) {
					return 'color';
				}

				return 'string';
			default:
				return $value[0];
		}
	}

	protected function lib_unit($args) {
		$num = $args[0];
		if ($num[0] == 'number') {
			return ['string', '"', [$num[2]]];
		}

		return '';
	}

	protected function lib_unitless($args) {
		$value = $args[0];

		return $value[0] == 'number' && empty($value[2]);
	}

	protected function lib_comparable($args) {
		list($number1, $number2) = $args;
		if (!isset($number1[0]) || $number1[0] != 'number' || !isset($number2[0]) || $number2[0] != 'number') {
			$this->throwError('Invalid argument(s) for "comparable"');
		}
		$number1 = $this->normalizeNumber($number1);
		$number2 = $this->normalizeNumber($number2);

		return $number1[2] == $number2[2] || $number1[2] == '' || $number2[2] == '';
	}

	/**
	 * Workaround IE7's content counter bug.
	 *
	 * @param array $args
	 */
	protected function lib_counter($args) {
		$list = array_map([$this, 'compileValue'], $args);

		return ['string', '', ['counter(' . implode(',', $list) . ')']];
	}
}

/**
 * SCSS parser
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
class titanscss_parser {
	static protected $precedence
		= [
			'or'  => 0,
			'and' => 1,
			'==' => 2,
			'!=' => 2,
			'<=' => 2,
			'>=' => 2,
			'='  => 2,
			'<'  => 3,
			'>'  => 2,
			'+' => 3,
			'-' => 3,
			'*' => 4,
			'/' => 4,
			'%' => 4,
		];
	static protected $operators
		= ['+', '-', '*', '/', '%',
		   '==', '!=', '<=', '>=', '<', '>', 'and', 'or'];
	static protected $operatorStr;
	static protected $whitePattern;
	static protected $commentMulti;
	static protected $commentSingle     = '//';
	static protected $commentMultiLeft  = '/*';
	static protected $commentMultiRight = '*/';

	/**
	 * Constructor
	 *
	 * @param string  $sourceName
	 * @param boolean $rootParser
	 */
	public function __construct($sourceName = NULL, $rootParser = TRUE) {
		$this->sourceName = $sourceName;
		$this->rootParser = $rootParser;
		if (empty(self::$operatorStr)) {
			self::$operatorStr = $this->makeOperatorStr(self::$operators);
			$commentSingle      = $this->preg_quote(self::$commentSingle);
			$commentMultiLeft   = $this->preg_quote(self::$commentMultiLeft);
			$commentMultiRight  = $this->preg_quote(self::$commentMultiRight);
			self::$commentMulti = $commentMultiLeft . '.*?' . $commentMultiRight;
			self::$whitePattern = '/' . $commentSingle . '[^\n]*\s*|(' . self::$commentMulti . ')\s*|\s+/Ais';
		}
	}

	static protected function makeOperatorStr($operators) {
		return '(' . implode('|', array_map(['titanscss_parser', 'preg_quote'],
		                                    $operators)) . ')';
	}

	static function preg_quote($what) {
		return preg_quote($what, '/');
	}

	/**
	 * Parser buffer
	 *
	 * @param string $buffer ;
	 *
	 * @return \StdClass
	 */
	public function parse($buffer) {
		$this->count           = 0;
		$this->env             = NULL;
		$this->inParens        = FALSE;
		$this->eatWhiteDefault = TRUE;
		$this->buffer          = $buffer;
		$this->pushBlock(NULL); // root block
		$this->whitespace();
		$this->pushBlock(NULL);
		$this->popBlock();
		while (FALSE != $this->parseChunk()) {
			;
		}
		if ($this->count != strlen($this->buffer)) {
			$this->throwParseError();
		}
		if (!empty($this->env->parent)) {
			$this->throwParseError('unclosed block');
		}
		$this->env->isRoot = TRUE;

		return $this->env;
	}

	protected function pushBlock($selectors) {
		$b         = new stdClass;
		$b->parent = $this->env; // not sure if we need this yet
		$b->selectors = $selectors;
		$b->comments  = [];
		if (!$this->env) {
			$b->children = [];
		} elseif (empty($this->env->children)) {
			$this->env->children = $this->env->comments;
			$b->children         = [];
			$this->env->comments = [];
		} else {
			$b->children         = $this->env->comments;
			$this->env->comments = [];
		}
		$this->env = $b;

		return $b;
	}

	protected function whitespace() {
		$gotWhite = FALSE;
		while (preg_match(self::$whitePattern, $this->buffer, $m, NULL, $this->count)) {
			if (isset($m[1]) && empty($this->commentsSeen[ $this->count ])) {
				$this->appendComment(['comment', $m[1]]);
				$this->commentsSeen[ $this->count ] = TRUE;
			}
			$this->count += strlen($m[0]);
			$gotWhite = TRUE;
		}

		return $gotWhite;
	}

	// tree builders
	protected function appendComment($comment) {
		$comment[1] = substr(preg_replace(['/^\s+/m', '/^(.)/m'], ['', ' \1'], $comment[1]), 1);
		$this->env->comments[] = $comment;
	}

	protected function popBlock() {
		$block = $this->env;
		if (empty($block->parent)) {
			$this->throwParseError('unexpected }');
		}
		$this->env = $block->parent;
		unset($block->parent);
		$comments = $block->comments;
		if (count($comments)) {
			$this->env->comments = $comments;
			unset($block->comments);
		}

		return $block;
	}

	public function throwParseError($msg = 'parse error', $count = NULL) {
		$count = !isset($count) ? $this->count : $count;
		$line = $this->getLineNo($count);
		if (!empty($this->sourceName)) {
			$loc = "$this->sourceName on line $line";
		} else {
			$loc = "line: $line";
		}
		if ($this->peek("(.*?)(\n|$)", $m, $count)) {
			throw new Exception("$msg: failed at `$m[1]` $loc");
		} else {
			throw new Exception("$msg: $loc");
		}
	}

	public function getLineNo($pos) {
		return 1 + substr_count(substr($this->buffer, 0, $pos), "\n");
	}

	protected function peek($regex, &$out, $from = NULL) {
		if (!isset($from)) $from = $this->count;
		$r      = '/' . $regex . '/Ais';
		$result = preg_match($r, $this->buffer, $out, NULL, $from);

		return $result;
	}

	// last child that was appended
	/**
	 * Parse a single chunk off the head of the buffer and append it to the
	 * current parse environment.
	 *
	 * Returns false when the buffer is empty, or when there is an error.
	 *
	 * This function is called repeatedly until the entire document is
	 * parsed.
	 *
	 * This parser is most similar to a recursive descent parser. Single
	 * functions represent discrete grammatical rules for the language, and
	 * they are able to capture the text that represents those rules.
	 *
	 * Consider the function scssc::keyword(). (All parse functions are
	 * structured the same.)
	 *
	 * The function takes a single reference argument. When calling the
	 * function it will attempt to match a keyword on the head of the buffer.
	 * If it is successful, it will place the keyword in the referenced
	 * argument, advance the position in the buffer, and return true. If it
	 * fails then it won't advance the buffer and it will return false.
	 *
	 * All of these parse functions are powered by scssc::match(), which behaves
	 * the same way, but takes a literal regular expression. Sometimes it is
	 * more convenient to use match instead of creating a new function.
	 *
	 * Because of the format of the functions, to parse an entire string of
	 * grammatical rules, you can chain them together using &&.
	 *
	 * But, if some of the rules in the chain succeed before one fails, then
	 * the buffer position will be left at an invalid state. In order to
	 * avoid this, scssc::seek() is used to remember and set buffer positions.
	 *
	 * Before parsing a chain, use $s = $this->seek() to remember the current
	 * position into $s. Then if a chain fails, use $this->seek($s) to
	 * go back where we started.
	 *
	 * @return boolean
	 */
	protected function parseChunk() {
		$s = $this->seek();
		// the directives
		if (isset($this->buffer[ $this->count ]) && $this->buffer[ $this->count ] == '@') {
			if ($this->literal('@media') && $this->mediaQueryList($mediaQueryList) && $this->literal('{')) {
				$media            = $this->pushSpecialBlock('media');
				$media->queryList = $mediaQueryList[2];

				return TRUE;
			} else {
				$this->seek($s);
			}
			if ($this->literal('@mixin') &&
			    $this->keyword($mixinName) &&
			    ($this->argumentDef($args) || TRUE) &&
			    $this->literal('{')
			) {
				$mixin       = $this->pushSpecialBlock('mixin');
				$mixin->name = $mixinName;
				$mixin->args = $args;

				return TRUE;
			} else {
				$this->seek($s);
			}
			if ($this->literal('@include') &&
			    $this->keyword($mixinName) &&
			    ($this->literal('(') &&
			     ($this->argValues($argValues) || TRUE) &&
			     $this->literal(')') || TRUE) &&
			    ($this->end() ||
			     $this->literal('{') && $hasBlock = TRUE)
			) {
				$child = ['include',
				          $mixinName, isset($argValues) ? $argValues : NULL, NULL];
				if (!empty($hasBlock)) {
					$include        = $this->pushSpecialBlock('include');
					$include->child = $child;
				} else {
					$this->append($child, $s);
				}

				return TRUE;
			} else {
				$this->seek($s);
			}
			if ($this->literal('@import') &&
			    $this->valueList($importPath) &&
			    $this->end()
			) {
				$this->append(['import', $importPath], $s);

				return TRUE;
			} else {
				$this->seek($s);
			}
			if ($this->literal('@extend') &&
			    $this->selectors($selector) &&
			    $this->end()
			) {
				$this->append(['extend', $selector], $s);

				return TRUE;
			} else {
				$this->seek($s);
			}
			if ($this->literal('@function') &&
			    $this->keyword($fnName) &&
			    $this->argumentDef($args) &&
			    $this->literal('{')
			) {
				$func       = $this->pushSpecialBlock('function');
				$func->name = $fnName;
				$func->args = $args;

				return TRUE;
			} else {
				$this->seek($s);
			}
			if ($this->literal('@return') && $this->valueList($retVal) && $this->end()) {
				$this->append(['return', $retVal], $s);

				return TRUE;
			} else {
				$this->seek($s);
			}
			if ($this->literal('@each') &&
			    $this->variable($varName) &&
			    $this->literal('in') &&
			    $this->valueList($list) &&
			    $this->literal('{')
			) {
				$each       = $this->pushSpecialBlock('each');
				$each->var  = $varName[1];
				$each->list = $list;

				return TRUE;
			} else {
				$this->seek($s);
			}
			if ($this->literal('@while') &&
			    $this->expression($cond) &&
			    $this->literal('{')
			) {
				$while       = $this->pushSpecialBlock('while');
				$while->cond = $cond;

				return TRUE;
			} else {
				$this->seek($s);
			}
			if ($this->literal('@for') &&
			    $this->variable($varName) &&
			    $this->literal('from') &&
			    $this->expression($start) &&
			    ($this->literal('through') ||
			     ($forUntil = TRUE && $this->literal('to'))) &&
			    $this->expression($end) &&
			    $this->literal('{')
			) {
				$for        = $this->pushSpecialBlock('for');
				$for->var   = $varName[1];
				$for->start = $start;
				$for->end   = $end;
				$for->until = isset($forUntil);

				return TRUE;
			} else {
				$this->seek($s);
			}
			if ($this->literal('@if') && $this->valueList($cond) && $this->literal('{')) {
				$if        = $this->pushSpecialBlock('if');
				$if->cond  = $cond;
				$if->cases = [];

				return TRUE;
			} else {
				$this->seek($s);
			}
			if (($this->literal('@debug') || $this->literal('@warn')) &&
			    $this->valueList($value) &&
			    $this->end()
			) {
				$this->append(['debug', $value, $s], $s);

				return TRUE;
			} else {
				$this->seek($s);
			}
			if ($this->literal('@content') && $this->end()) {
				$this->append(['mixin_content'], $s);

				return TRUE;
			} else {
				$this->seek($s);
			}
			$last = $this->last();
			if (isset($last) && $last[0] == 'if') {
				list(, $if) = $last;
				if ($this->literal('@else')) {
					if ($this->literal('{')) {
						$else = $this->pushSpecialBlock('else');
					} elseif ($this->literal('if') && $this->valueList($cond) && $this->literal('{')) {
						$else       = $this->pushSpecialBlock('elseif');
						$else->cond = $cond;
					}
					if (isset($else)) {
						$else->dontAppend = TRUE;
						$if->cases[]      = $else;

						return TRUE;
					}
				}
				$this->seek($s);
			}
			if ($this->literal('@charset') &&
			    $this->valueList($charset) && $this->end()
			) {
				$this->append(['charset', $charset], $s);

				return TRUE;
			} else {
				$this->seek($s);
			}
			// doesn't match built in directive, do generic one
			if ($this->literal('@', FALSE) && $this->keyword($dirName) &&
			    ($this->variable($dirValue) || $this->openString('{', $dirValue) || TRUE) &&
			    $this->literal('{')
			) {
				$directive       = $this->pushSpecialBlock('directive');
				$directive->name = $dirName;
				if (isset($dirValue)) $directive->value = $dirValue;

				return TRUE;
			}
			$this->seek($s);

			return FALSE;
		}
		// property shortcut
		// captures most properties before having to parse a selector
		if ($this->keyword($name, FALSE) &&
		    $this->literal(': ') &&
		    $this->valueList($value) &&
		    $this->end()
		) {
			$name = ['string', '', [$name]];
			$this->append(['assign', $name, $value], $s);

			return TRUE;
		} else {
			$this->seek($s);
		}
		// variable assigns
		if ($this->variable($name) &&
		    $this->literal(':') &&
		    $this->valueList($value) && $this->end()
		) {
			// check for !default
			$defaultVar = $value[0] == 'list' && $this->stripDefault($value);
			$this->append(['assign', $name, $value, $defaultVar], $s);

			return TRUE;
		} else {
			$this->seek($s);
		}
		// misc
		if ($this->literal('-->')) {
			return TRUE;
		}
		// opening css block
		if ($this->selectors($selectors) && $this->literal('{')) {
			$b = $this->pushBlock($selectors);

			return TRUE;
		} else {
			$this->seek($s);
		}
		// property assign, or nested assign
		if ($this->propertyName($name) && $this->literal(':')) {
			$foundSomething = FALSE;
			if ($this->valueList($value)) {
				$this->append(['assign', $name, $value], $s);
				$foundSomething = TRUE;
			}
			if ($this->literal('{')) {
				$propBlock         = $this->pushSpecialBlock('nestedprop');
				$propBlock->prefix = $name;
				$foundSomething    = TRUE;
			} elseif ($foundSomething) {
				$foundSomething = $this->end();
			}
			if ($foundSomething) {
				return TRUE;
			}
			$this->seek($s);
		} else {
			$this->seek($s);
		}
		// closing a block
		if ($this->literal('}')) {
			$block = $this->popBlock();
			if (isset($block->type) && $block->type == 'include') {
				$include = $block->child;
				unset($block->child);
				$include[3] = $block;
				$this->append($include, $s);
			} elseif (empty($block->dontAppend)) {
				$type = isset($block->type) ? $block->type : 'block';
				$this->append([$type, $block], $s);
			}

			return TRUE;
		}
		// extra stuff
		if ($this->literal(';') ||
		    $this->literal('<!--')
		) {
			return TRUE;
		}

		return FALSE;
	}

	// high level parsers (they return parts of ast)
	protected function seek($where = NULL) {
		if ($where == NULL) {
			return $this->count;
		} else $this->count = $where;

		return TRUE;
	}

	protected function literal($what, $eatWhitespace = NULL) {
		if (!isset($eatWhitespace)) $eatWhitespace = $this->eatWhiteDefault;
		// shortcut on single letter
		if (!isset($what[1]) && isset($this->buffer[ $this->count ])) {
			if ($this->buffer[ $this->count ] == $what) {
				if (!$eatWhitespace) {
					$this->count++;

					return TRUE;
				}
				// goes below...
			} else {
				return FALSE;
			}
		}

		return $this->match($this->preg_quote($what), $m, $eatWhitespace);
	}

	protected function match($regex, &$out, $eatWhitespace = NULL) {
		if (!isset($eatWhitespace)) $eatWhitespace = $this->eatWhiteDefault;
		$r = '/' . $regex . '/Ais';
		if (preg_match($r, $this->buffer, $out, NULL, $this->count)) {
			$this->count += strlen($out[0]);
			if ($eatWhitespace) {
				$this->whitespace();
			}

			return TRUE;
		}

		return FALSE;
	}

	protected function mediaQueryList(&$out) {
		return $this->genericList($out, 'mediaQuery', ',', FALSE);
	}

	protected function genericList(&$out, $parseItem, $delim = '', $flatten = TRUE) {
		$s     = $this->seek();
		$items = [];
		while ($this->$parseItem($value)) {
			$items[] = $value;
			if ($delim) {
				if (!$this->literal($delim)) break;
			}
		}
		if (count($items) == 0) {
			$this->seek($s);

			return FALSE;
		}
		if ($flatten && count($items) == 1) {
			$out = $items[0];
		} else {
			$out = ['list', $delim, $items];
		}

		return TRUE;
	}

	protected function pushSpecialBlock($type) {
		$block       = $this->pushBlock(NULL);
		$block->type = $type;

		return $block;
	}

	protected function keyword(&$word, $eatWhitespace = NULL) {
		if ($this->match('(([\w_\-\*!"\']|[\\\\].)([\w\-_"\']|[\\\\].)*)',
		                 $m, $eatWhitespace)
		) {
			$word = $m[1];

			return TRUE;
		}

		return FALSE;
	}

	protected function argumentDef(&$out) {
		$s = $this->seek();
		$this->literal('(');
		$args = [];
		while ($this->variable($var)) {
			$arg = [$var[1], NULL, FALSE];
			$ss = $this->seek();
			if ($this->literal(':') && $this->genericList($defaultVal, 'expression')) {
				$arg[1] = $defaultVal;
			} else {
				$this->seek($ss);
			}
			$ss = $this->seek();
			if ($this->literal('...')) {
				$sss = $this->seek();
				if (!$this->literal(')')) {
					$this->throwParseError('... has to be after the final argument');
				}
				$arg[2] = TRUE;
				$this->seek($sss);
			} else {
				$this->seek($ss);
			}
			$args[] = $arg;
			if (!$this->literal(',')) break;
		}
		if (!$this->literal(')')) {
			$this->seek($s);

			return FALSE;
		}
		$out = $args;

		return TRUE;
	}

	protected function variable(&$out) {
		$s = $this->seek();
		if ($this->literal('$', FALSE) && $this->keyword($name)) {
			$out = ['var', $name];

			return TRUE;
		}
		$this->seek($s);

		return FALSE;
	}

	protected function argValues(&$out) {
		if ($this->genericList($list, 'argValue', ',', FALSE)) {
			$out = $list[2];

			return TRUE;
		}

		return FALSE;
	}

	protected function end() {
		if ($this->literal(';')) {
			return TRUE;
		} elseif ($this->count == strlen($this->buffer) || $this->buffer[ $this->count ] == '}') {
			// if there is end of file or a closing block next then we don't need a ;
			return TRUE;
		}

		return FALSE;
	}

	// value wrappen in parentheses
	protected function append($statement, $pos = NULL) {
		if ($pos != NULL) {
			$statement[ -1 ] = $pos;
			if (!$this->rootParser) $statement[ -2 ] = $this;
		}
		$this->env->children[] = $statement;
		$comments = $this->env->comments;
		if (count($comments)) {
			$this->env->children = array_merge($this->env->children, $comments);
			$this->env->comments = [];
		}
	}

	/**
	 * Parse list
	 *
	 * @param string $out
	 *
	 * @return boolean
	 */
	public function valueList(&$out) {
		return $this->genericList($out, 'spaceList', ',');
	}

	protected function selectors(&$out) {
		$s         = $this->seek();
		$selectors = [];
		while ($this->selector($sel)) {
			$selectors[] = $sel;
			if (!$this->literal(',')) break;
			while ($this->literal(',')) ; // ignore extra
		}
		if (count($selectors) == 0) {
			$this->seek($s);

			return FALSE;
		}
		$out = $selectors;

		return TRUE;
	}

	protected function selector(&$out) {
		$selector = [];
		while (TRUE) {
			if ($this->match('[>+~]+', $m)) {
				$selector[] = [$m[0]];
			} elseif ($this->selectorSingle($part)) {
				$selector[] = $part;
				$this->match('\s+', $m);
			} elseif ($this->match('\/[^\/]+\/', $m)) {
				$selector[] = [$m[0]];
			} else {
				break;
			}
		}
		if (count($selector) == 0) {
			return FALSE;
		}
		$out = $selector;

		return TRUE;
	}

	protected function selectorSingle(&$out) {
		$oldWhite              = $this->eatWhiteDefault;
		$this->eatWhiteDefault = FALSE;
		$parts = [];
		if ($this->literal('*', FALSE)) {
			$parts[] = '*';
		}
		while (TRUE) {
			// see if we can stop early
			if ($this->match('\s*[{,]', $m)) {
				$this->count--;
				break;
			}
			$s = $this->seek();
			// self
			if ($this->literal('&', FALSE)) {
				$parts[] = titanscssc::$selfSelector;
				continue;
			}
			if ($this->literal('.', FALSE)) {
				$parts[] = '.';
				continue;
			}
			if ($this->literal('|', FALSE)) {
				$parts[] = '|';
				continue;
			}
			if ($this->match('\\\\\S', $m)) {
				$parts[] = $m[0];
				continue;
			}
			// for keyframes
			if ($this->unit($unit)) {
				$parts[] = $unit;
				continue;
			}
			if ($this->keyword($name)) {
				$parts[] = $name;
				continue;
			}
			if ($this->interpolation($inter)) {
				$parts[] = $inter;
				continue;
			}
			if ($this->literal('%', FALSE) && $this->placeholder($placeholder)) {
				$parts[] = '%';
				$parts[] = $placeholder;
				continue;
			}
			if ($this->literal('#', FALSE)) {
				$parts[] = '#';
				continue;
			}
			// a pseudo selector
			if ($this->match('::?', $m) && $this->mixedKeyword($nameParts)) {
				$parts[] = $m[0];
				foreach ($nameParts as $sub) {
					$parts[] = $sub;
				}
				$ss = $this->seek();
				if ($this->literal('(') &&
				    ($this->openString(')', $str, '(') || TRUE) &&
				    $this->literal(')')
				) {
					$parts[] = '(';
					if (!empty($str)) $parts[] = $str;
					$parts[] = ')';
				} else {
					$this->seek($ss);
				}
				continue;
			} else {
				$this->seek($s);
			}
			// attribute selector
			// TODO: replace with open string?
			if ($this->literal('[', FALSE)) {
				$attrParts = ['['];
				// keyword, string, operator
				while (TRUE) {
					if ($this->literal(']', FALSE)) {
						$this->count--;
						break; // get out early
					}
					if ($this->match('\s+', $m)) {
						$attrParts[] = ' ';
						continue;
					}
					if ($this->string($str)) {
						$attrParts[] = $str;
						continue;
					}
					if ($this->keyword($word)) {
						$attrParts[] = $word;
						continue;
					}
					if ($this->interpolation($inter, FALSE)) {
						$attrParts[] = $inter;
						continue;
					}
					// operator, handles attr namespace too
					if ($this->match('[|-~\$\*\^=]+', $m)) {
						$attrParts[] = $m[0];
						continue;
					}
					break;
				}
				if ($this->literal(']', FALSE)) {
					$attrParts[] = ']';
					foreach ($attrParts as $part) {
						$parts[] = $part;
					}
					continue;
				}
				$this->seek($s);
				// should just break here?
			}
			break;
		}
		$this->eatWhiteDefault = $oldWhite;
		if (count($parts) == 0) return FALSE;
		$out = $parts;

		return TRUE;
	}

	protected function unit(&$unit) {
		if ($this->match('([0-9]*(\.)?[0-9]+)([%a-zA-Z]+)?', $m)) {
			$unit = ['number', $m[1], empty($m[3]) ? '' : $m[3]];

			return TRUE;
		}

		return FALSE;
	}

	protected function interpolation(&$out, $lookWhite = TRUE) {
		$oldWhite              = $this->eatWhiteDefault;
		$this->eatWhiteDefault = TRUE;
		$s = $this->seek();
		if ($this->literal('#{') && $this->valueList($value) && $this->literal('}', FALSE)) {
			// TODO: don't error if out of bounds
			if ($lookWhite) {
				$left  = preg_match('/\s/', $this->buffer[ $s - 1 ]) ? ' ' : '';
				$right = preg_match('/\s/', $this->buffer[ $this->count ]) ? ' ' : '';
			} else {
				$left = $right = FALSE;
			}
			$out                   = ['interpolate', $value, $left, $right];
			$this->eatWhiteDefault = $oldWhite;
			if ($this->eatWhiteDefault) {
				$this->whitespace();
			}

			return TRUE;
		}
		$this->seek($s);
		$this->eatWhiteDefault = $oldWhite;

		return FALSE;
	}

	protected function placeholder(&$placeholder) {
		if ($this->match('([\w\-_]+)', $m)) {
			$placeholder = $m[1];

			return TRUE;
		}

		return FALSE;
	}

	protected function mixedKeyword(&$out) {
		$s = $this->seek();
		$parts = [];
		$oldWhite              = $this->eatWhiteDefault;
		$this->eatWhiteDefault = FALSE;
		while (TRUE) {
			if ($this->keyword($key)) {
				$parts[] = $key;
				continue;
			}
			if ($this->interpolation($inter)) {
				$parts[] = $inter;
				continue;
			}
			break;
		}
		$this->eatWhiteDefault = $oldWhite;
		if (count($parts) == 0) return FALSE;
		if ($this->eatWhiteDefault) {
			$this->whitespace();
		}
		$out = $parts;

		return TRUE;
	}

	// an unbounded string stopped by $end
	protected function openString($end, &$out, $nestingOpen = NULL) {
		$oldWhite              = $this->eatWhiteDefault;
		$this->eatWhiteDefault = FALSE;
		$stop   = ['\'', '"', '#{', $end];
		$stop   = array_map([$this, 'preg_quote'], $stop);
		$stop[] = self::$commentMulti;
		$patt = '(.*?)(' . implode('|', $stop) . ')';
		$nestingLevel = 0;
		$content = [];
		while ($this->match($patt, $m, FALSE)) {
			if (isset($m[1]) && $m[1] != '') {
				$content[] = $m[1];
				if ($nestingOpen) {
					$nestingLevel += substr_count($m[1], $nestingOpen);
				}
			}
			$tok = $m[2];
			$this->count -= strlen($tok);
			if ($tok == $end) {
				if ($nestingLevel == 0) {
					break;
				} else {
					$nestingLevel--;
				}
			}
			if (($tok == '\'' || $tok == '"') && $this->string($str)) {
				$content[] = $str;
				continue;
			}
			if ($tok == '#{' && $this->interpolation($inter)) {
				$content[] = $inter;
				continue;
			}
			$content[] = $tok;
			$this->count += strlen($tok);
		}
		$this->eatWhiteDefault = $oldWhite;
		if (count($content) == 0) return FALSE;
		// trim the end
		if (is_string(end($content))) {
			$content[ count($content) - 1 ] = rtrim(end($content));
		}
		$out = ['string', '', $content];

		return TRUE;
	}

	// $lookWhite: save information about whitespace before and after
	protected function string(&$out) {
		$s = $this->seek();
		if ($this->literal('"', FALSE)) {
			$delim = '"';
		} elseif ($this->literal('\'', FALSE)) {
			$delim = '\'';
		} else {
			return FALSE;
		}
		$content               = [];
		$oldWhite              = $this->eatWhiteDefault;
		$this->eatWhiteDefault = FALSE;
		while ($this->matchString($m, $delim)) {
			$content[] = $m[1];
			if ($m[2] == '#{') {
				$this->count -= strlen($m[2]);
				if ($this->interpolation($inter, FALSE)) {
					$content[] = $inter;
				} else {
					$this->count += strlen($m[2]);
					$content[] = '#{'; // ignore it
				}
			} elseif ($m[2] == '\\') {
				$content[] = $m[2];
				if ($this->literal($delim, FALSE)) {
					$content[] = $delim;
				}
			} else {
				$this->count -= strlen($delim);
				break; // delim
			}
		}
		$this->eatWhiteDefault = $oldWhite;
		if ($this->literal($delim)) {
			$out = ['string', $delim, $content];

			return TRUE;
		}
		$this->seek($s);

		return FALSE;
	}

	// low level parsers
	// returns an array of parts or a string
	/**
	 * Match string looking for either ending delim, escape, or string interpolation
	 *
	 * {@internal This is a workaround for preg_match's 250K string match limit. }}
	 *
	 * @param array  $m     Matches (passed by reference)
	 * @param string $delim Delimeter
	 *
	 * @return boolean True if match; false otherwise
	 */
	protected function matchString(&$m, $delim) {
		$token = NULL;
		$end = strpos($this->buffer, "\n", $this->count);
		if ($end == FALSE || $this->buffer[ $end - 1 ] == '\\' || $this->buffer[ $end - 2 ] == '\\' && $this->buffer[ $end - 1 ] == "\r") {
			$end = strlen($this->buffer);
		}
		// look for either ending delim, escape, or string interpolation
		foreach (['#{', '\\', $delim] as $lookahead) {
			$pos = strpos($this->buffer, $lookahead, $this->count);
			if ($pos != FALSE && $pos < $end) {
				$end   = $pos;
				$token = $lookahead;
			}
		}
		if (!isset($token)) {
			return FALSE;
		}
		$match       = substr($this->buffer, $this->count, $end - $this->count);
		$m           = [
			$match . $token,
			$match,
			$token
		];
		$this->count = $end + strlen($token);

		return TRUE;
	}

	// comma separated list of selectors
	protected function expression(&$out) {
		$s = $this->seek();
		if ($this->literal('(')) {
			if ($this->literal(')')) {
				$out = ['list', '', []];

				return TRUE;
			}
			if ($this->valueList($out) && $this->literal(')') && $out[0] == 'list') {
				return TRUE;
			}
			$this->seek($s);
		}
		if ($this->value($lhs)) {
			$out = $this->expHelper($lhs, 0);

			return TRUE;
		}

		return FALSE;
	}

	// whitespace separated list of selectorSingle
	protected function value(&$out) {
		$s = $this->seek();
		if ($this->literal('not', FALSE) && $this->whitespace() && $this->value($inner)) {
			$out = ['unary', 'not', $inner, $this->inParens];

			return TRUE;
		} else {
			$this->seek($s);
		}
		if ($this->literal('+') && $this->value($inner)) {
			$out = ['unary', '+', $inner, $this->inParens];

			return TRUE;
		} else {
			$this->seek($s);
		}
		// negation
		if ($this->literal('-', FALSE) &&
		    ($this->variable($inner) ||
		     $this->unit($inner) ||
		     $this->parenValue($inner))
		) {
			$out = ['unary', '-', $inner, $this->inParens];

			return TRUE;
		} else {
			$this->seek($s);
		}
		if ($this->parenValue($out)) return TRUE;
		if ($this->interpolation($out)) return TRUE;
		if ($this->variable($out)) return TRUE;
		if ($this->color($out)) return TRUE;
		if ($this->unit($out)) return TRUE;
		if ($this->string($out)) return TRUE;
		if ($this->func($out)) return TRUE;
		if ($this->progid($out)) return TRUE;
		if ($this->keyword($keyword)) {
			if ($keyword == 'null') {
				$out = ['null'];
			} else {
				$out = ['keyword', $keyword];
			}

			return TRUE;
		}

		return FALSE;
	}

	// the parts that make up
	// div[yes=no]#something.hello.world:nth-child(-2n+1)%placeholder
	protected function parenValue(&$out) {
		$s = $this->seek();
		$inParens = $this->inParens;
		if ($this->literal('(') &&
		    ($this->inParens = TRUE) && $this->expression($exp) &&
		    $this->literal(')')
		) {
			$out            = $exp;
			$this->inParens = $inParens;

			return TRUE;
		} else {
			$this->inParens = $inParens;
			$this->seek($s);
		}

		return FALSE;
	}

	protected function color(&$out) {
		$color = ['color'];
		if ($this->match('(#([0-9a-f]{6})|#([0-9a-f]{3}))', $m)) {
			if (isset($m[3])) {
				$num   = $m[3];
				$width = 16;
			} else {
				$num   = $m[2];
				$width = 256;
			}
			$num = hexdec($num);
			foreach ([3, 2, 1] as $i) {
				$t = $num % $width;
				$num /= $width;
				$color[ $i ] = $t * (256 / $width) + $t * floor(16 / $width);
			}
			$out = $color;

			return TRUE;
		}

		return FALSE;
	}

	protected function func(&$func) {
		$s = $this->seek();
		if ($this->keyword($name, FALSE) &&
		    $this->literal('(')
		) {
			if ($name == 'alpha' && $this->argumentList($args)) {
				$func = ['function', $name, ['string', '', $args]];

				return TRUE;
			}
			if ($name != 'expression' && !preg_match('/^(-[a-z]+-)?calc$/', $name)) {
				$ss = $this->seek();
				if ($this->argValues($args) && $this->literal(')')) {
					$func = ['fncall', $name, $args];

					return TRUE;
				}
				$this->seek($ss);
			}
			if (($this->openString(')', $str, '(') || TRUE) &&
			    $this->literal(')')
			) {
				$args = [];
				if (!empty($str)) {
					$args[] = [NULL, ['string', '', [$str]]];
				}
				$func = ['fncall', $name, $args];

				return TRUE;
			}
		}
		$this->seek($s);

		return FALSE;
	}

	protected function argumentList(&$out) {
		$s = $this->seek();
		$this->literal('(');
		$args = [];
		while ($this->keyword($var)) {
			$ss = $this->seek();
			if ($this->literal('=') && $this->expression($exp)) {
				$args[] = ['string', '', [$var . '=']];
				$arg    = $exp;
			} else {
				break;
			}
			$args[] = $arg;
			if (!$this->literal(',')) break;
			$args[] = ['string', '', [', ']];
		}
		if (!$this->literal(')') || !count($args)) {
			$this->seek($s);

			return FALSE;
		}
		$out = $args;

		return TRUE;
	}

	// consume an end of statement delimiter
	protected function progid(&$out) {
		$s = $this->seek();
		if ($this->literal('progid:', FALSE) &&
		    $this->openString('(', $fn) &&
		    $this->literal('(')
		) {
			$this->openString(')', $args, '(');
			if ($this->literal(')')) {
				$out = ['string', '', [
					'progid:', $fn, '(', $args, ')'
				]];

				return TRUE;
			}
		}
		$this->seek($s);

		return FALSE;
	}

	// advance counter to next occurrence of $what
	// $until - don't include $what in advance
	// $allowNewline, if string, will be used as valid char set
	protected function expHelper($lhs, $minP) {
		$opstr = self::$operatorStr;
		$ss          = $this->seek();
		$whiteBefore = isset($this->buffer[ $this->count - 1 ]) &&
		               ctype_space($this->buffer[ $this->count - 1 ]);
		while ($this->match($opstr, $m) && self::$precedence[ $m[1] ] >= $minP) {
			$whiteAfter = isset($this->buffer[ $this->count - 1 ]) &&
			              ctype_space($this->buffer[ $this->count - 1 ]);
			$op = $m[1];
			// don't turn negative numbers into expressions
			if ($op == '-' && $whiteBefore) {
				if (!$whiteAfter) break;
			}
			if (!$this->value($rhs)) break;
			// peek and see if rhs belongs to next operator
			if ($this->peek($opstr, $next) && self::$precedence[ $next[1] ] > self::$precedence[ $op ]) {
				$rhs = $this->expHelper($rhs, self::$precedence[ $next[1] ]);
			}
			$lhs         = ['exp', $op, $lhs, $rhs, $this->inParens, $whiteBefore, $whiteAfter];
			$ss          = $this->seek();
			$whiteBefore = isset($this->buffer[ $this->count - 1 ]) &&
			               ctype_space($this->buffer[ $this->count - 1 ]);
		}
		$this->seek($ss);

		return $lhs;
	}

	protected function last() {
		$i = count($this->env->children) - 1;
		if (isset($this->env->children[ $i ])) {
			return $this->env->children[ $i ];
		}
	}

	protected function stripDefault(&$value) {
		$def = end($value[2]);
		if ($def[0] == 'keyword' && $def[1] == '!default') {
			array_pop($value[2]);
			$value = $this->flattenList($value);

			return TRUE;
		}
		if ($def[0] == 'list') {
			return $this->stripDefault($value[2][ count($value[2]) - 1 ]);
		}

		return FALSE;
	}

	protected function flattenList($value) {
		if ($value[0] == 'list' && count($value[2]) == 1) {
			return $this->flattenList($value[2][0]);
		}

		return $value;
	}

	// try to match something on head of buffer
	protected function propertyName(&$out) {
		$s     = $this->seek();
		$parts = [];
		$oldWhite              = $this->eatWhiteDefault;
		$this->eatWhiteDefault = FALSE;
		while (TRUE) {
			if ($this->interpolation($inter)) {
				$parts[] = $inter;
			} elseif ($this->keyword($text)) {
				$parts[] = $text;
			} elseif (count($parts) == 0 && $this->match('[:.#]', $m, FALSE)) {
				// css hacks
				$parts[] = $m[0];
			} else {
				break;
			}
		}
		$this->eatWhiteDefault = $oldWhite;
		if (count($parts) == 0) return FALSE;
		// match comment hack
		if (preg_match(self::$whitePattern,
		               $this->buffer, $m, NULL, $this->count)) {
			if (!empty($m[0])) {
				$parts[] = $m[0];
				$this->count += strlen($m[0]);
			}
		}
		$this->whitespace(); // get any extra whitespace
		$out = ['string', '', $parts];

		return TRUE;
	}

	// match some whitespace
	protected function mediaQuery(&$out) {
		$s = $this->seek();
		$expressions = NULL;
		$parts       = [];
		if (($this->literal('only') && ($only = TRUE) || $this->literal('not') && ($not
					= TRUE) || TRUE) && $this->mixedKeyword($mediaType)
		) {
			$prop = ['mediaType'];
			if (isset($only)) $prop[] = ['keyword', 'only'];
			if (isset($not)) $prop[] = ['keyword', 'not'];
			$media = ['list', '', []];
			foreach ((array)$mediaType as $type) {
				if (is_array($type)) {
					$media[2][] = $type;
				} else {
					$media[2][] = ['keyword', $type];
				}
			}
			$prop[]  = $media;
			$parts[] = $prop;
		}
		if (empty($parts) || $this->literal('and')) {
			$this->genericList($expressions, 'mediaExpression', 'and', FALSE);
			if (is_array($expressions)) $parts = array_merge($parts, $expressions[2]);
		}
		$out = $parts;

		return TRUE;
	}

	protected function mediaExpression(&$out) {
		$s     = $this->seek();
		$value = NULL;
		if ($this->literal('(') &&
		    $this->expression($feature) &&
		    ($this->literal(':') && $this->expression($value) || TRUE) &&
		    $this->literal(')')
		) {
			$out = ['mediaExp', $feature];
			if ($value) $out[] = $value;

			return TRUE;
		}
		$this->seek($s);

		return FALSE;
	}

	protected function argValue(&$out) {
		$s = $this->seek();
		$keyword = NULL;
		if (!$this->variable($keyword) || !$this->literal(':')) {
			$this->seek($s);
			$keyword = NULL;
		}
		if ($this->genericList($value, 'expression')) {
			$out = [$keyword, $value, FALSE];
			$s   = $this->seek();
			if ($this->literal('...')) {
				$out[2] = TRUE;
			} else {
				$this->seek($s);
			}

			return TRUE;
		}

		return FALSE;
	}

	protected function spaceList(&$out) {
		return $this->genericList($out, 'expression');
	}

	protected function to($what, &$out, $until = FALSE, $allowNewline = FALSE) {
		if (is_string($allowNewline)) {
			$validChars = $allowNewline;
		} else {
			$validChars = $allowNewline ? '.' : "[^\n]";
		}
		if (!$this->match('(' . $validChars . '*?)' . $this->preg_quote($what), $m, !$until)) return FALSE;
		if ($until) $this->count -= strlen($what); // give back $what
		$out = $m[1];

		return TRUE;
	}

	// turn list of length 1 into value type
	protected function show() {
		if ($this->peek("(.*?)(\n|$)", $m, $this->count)) {
			return $m[1];
		}

		return '';
	}
}

/**
 * SCSS base formatter
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
class titanscss_formatter {
	public $indentChar = '  ';
	public $break           = "\n";
	public $open            = ' {';
	public $close           = '}';
	public $tagSeparator    = ', ';
	public $assignSeparator = ': ';

	public function __construct() {
		$this->indentLevel = 0;
	}

	public function property($name, $value) {
		return $name . $this->assignSeparator . $value . ';';
	}

	public function format($block) {
		ob_start();
		$this->block($block);
		$out = ob_get_clean();

		return $out;
	}

	protected function block($block) {
		if (empty($block->lines) && empty($block->children)) return;
		$inner = $pre = $this->indentStr();
		if (!empty($block->selectors)) {
			echo $pre .
			     implode($this->tagSeparator, $block->selectors) .
			     $this->open . $this->break;
			$this->indentLevel++;
			$inner = $this->indentStr();
		}
		if (!empty($block->lines)) {
			$this->blockLines($inner, $block);
		}
		foreach ($block->children as $child) {
			$this->block($child);
		}
		if (!empty($block->selectors)) {
			$this->indentLevel--;
			if (empty($block->children)) echo $this->break;
			echo $pre . $this->close . $this->break;
		}
	}

	public function indentStr($n = 0) {
		return str_repeat($this->indentChar, max($this->indentLevel + $n, 0));
	}

	protected function blockLines($inner, $block) {
		$glue = $this->break . $inner;
		echo $inner . implode($glue, $block->lines);
		if (!empty($block->children)) {
			echo $this->break;
		}
	}
}

/**
 * SCSS nested formatter
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
class titanscss_formatter_nested extends titanscss_formatter {
	public $close = ' }';

	// adjust the depths of all children, depth first
	public function adjustAllChildren($block) {
		// flatten empty nested blocks
		$children = [];
		foreach ($block->children as $i => $child) {
			if (empty($child->lines) && empty($child->children)) {
				if (isset($block->children[ $i + 1 ])) {
					$block->children[ $i + 1 ]->depth = $child->depth;
				}
				continue;
			}
			$children[] = $child;
		}
		$count = count($children);
		for ($i = 0; $i < $count; $i++) {
			$depth = $children[ $i ]->depth;
			$j     = $i + 1;
			if (isset($children[ $j ]) && $depth < $children[ $j ]->depth) {
				$childDepth = $children[ $j ]->depth;
				for (; $j < $count; $j++) {
					if ($depth < $children[ $j ]->depth && $childDepth >= $children[ $j ]->depth) {
						$children[ $j ]->depth = $depth + 1;
					}
				}
			}
		}
		$block->children = $children;
		// make relative to parent
		foreach ($block->children as $child) {
			$this->adjustAllChildren($child);
			$child->depth = $child->depth - $block->depth;
		}
	}

	protected function blockLines($inner, $block) {
		$glue = $this->break . $inner;
		foreach ($block->lines as $index => $line) {
			if (substr($line, 0, 2) == '/*') {
				$block->lines[ $index ] = preg_replace('/(\r|\n)+/', $glue, $line);
			}
		}
		echo $inner . implode($glue, $block->lines);
		if (!empty($block->children)) {
			echo $this->break;
		}
	}

	protected function block($block) {
		if ($block->type == 'root') {
			$this->adjustAllChildren($block);
		}
		$inner = $pre = $this->indentStr($block->depth - 1);
		if (!empty($block->selectors)) {
			echo $pre .
			     implode($this->tagSeparator, $block->selectors) .
			     $this->open . $this->break;
			$this->indentLevel++;
			$inner = $this->indentStr($block->depth - 1);
		}
		if (!empty($block->lines)) {
			$this->blockLines($inner, $block);
		}
		foreach ($block->children as $i => $child) {
			// echo "*** block: ".$block->depth." child: ".$child->depth."\n";
			$this->block($child);
			if ($i < count($block->children) - 1) {
				echo $this->break;
				if (isset($block->children[ $i + 1 ])) {
					$next = $block->children[ $i + 1 ];
					if ($next->depth == max($block->depth, 1) && $child->depth >= $next->depth) {
						echo $this->break;
					}
				}
			}
		}
		if (!empty($block->selectors)) {
			$this->indentLevel--;
			echo $this->close;
		}
		if ($block->type == 'root') {
			echo $this->break;
		}
	}
}

/**
 * SCSS compressed formatter
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
class titanscss_formatter_compressed extends titanscss_formatter {
	public $open            = '{';
	public $tagSeparator    = ',';
	public $assignSeparator = ':';
	public $break           = '';

	public function indentStr($n = 0) {
		return '';
	}

	public function blockLines($inner, $block) {
		$glue = $this->break . $inner;
		foreach ($block->lines as $index => $line) {
			if (substr($line, 0, 2) == '/*' && substr($line, 2, 1) != '!') {
				unset($block->lines[ $index ]);
			} elseif (substr($line, 0, 3) == '/*!') {
				$block->lines[ $index ] = '/*' . substr($line, 3);
			}
		}
		echo $inner . implode($glue, $block->lines);
		if (!empty($block->children)) {
			echo $this->break;
		}
	}
}

/**
 * SCSS crunched formatter
 *
 * @author Anthon Pang <anthon.pang@gmail.com>
 */
class titanscss_formatter_crunched extends titanscss_formatter {
	public $open            = '{';
	public $tagSeparator    = ',';
	public $assignSeparator = ':';
	public $break           = '';

	public function indentStr($n = 0) {
		return '';
	}

	public function blockLines($inner, $block) {
		$glue = $this->break . $inner;
		foreach ($block->lines as $index => $line) {
			if (substr($line, 0, 2) == '/*') {
				unset($block->lines[ $index ]);
			}
		}
		echo $inner . implode($glue, $block->lines);
		if (!empty($block->children)) {
			echo $this->break;
		}
	}
}

/**
 * SCSS server
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
class titanscss_server {
	/**
	 * Constructor
	 *
	 * @param string      $dir      Root directory to .scss files
	 * @param string      $cacheDir Cache directory
	 * @param \scssc|null $scss     SCSS compiler instance
	 */
	public function __construct($dir, $cacheDir = NULL, $scss = NULL) {
		$this->dir = $dir;
		if (!isset($cacheDir)) {
			$cacheDir = $this->join($dir, 'titanscss_cache');
		}
		$this->cacheDir = $cacheDir;
		if (!is_dir($this->cacheDir)) mkdir($this->cacheDir, 0755, TRUE);
		if (!isset($scss)) {
			$scss = new titanscssc();
			$scss->setImportPaths($this->dir);
		}
		$this->scss = $scss;
	}

	/**
	 * Join path components
	 *
	 * @param string $left  Path component, left of the directory separator
	 * @param string $right Path component, right of the directory separator
	 *
	 * @return string
	 */
	protected function join($left, $right) {
		return rtrim($left, '/\\') . DIRECTORY_SEPARATOR . ltrim($right, '/\\');
	}

	/**
	 * Helper method to serve compiled scss
	 *
	 * @param string $path Root path
	 */
	static public function serveFrom($path) {
		$server = new self($path);
		$server->serve();
	}

	/**
	 * Compile requested scss and serve css.  Outputs HTTP response.
	 *
	 * @param string $salt Prefix a string to the filename for creating the cache name hash
	 */
	public function serve($salt = '') {
		$protocol = isset($_SERVER['SERVER_PROTOCOL'])
			? $_SERVER['SERVER_PROTOCOL']
			: 'HTTP/1.0';
		if ($input = $this->findInput()) {
			$output = $this->cacheName($salt . $input);
			$etag   = $noneMatch = trim($this->getIfNoneMatchHeader(), '"');
			if ($this->needsCompile($input, $output, $etag)) {
				try {
					list($css, $etag) = $this->compile($input, $output);
					$lastModified = gmdate('D, d M Y H:i:s', filemtime($output)) . ' GMT';
					header('Last-Modified: ' . $lastModified);
					header('Content-type: text/css');
					header('ETag: "' . $etag . '"');
					echo $css;

					return;
				} catch (Exception $e) {
					header($protocol . ' 500 Internal Server Error');
					header('Content-type: text/plain');
					echo 'Parse error: ' . $e->getMessage() . "\n";
				}
			}
			header('X-SCSS-Cache: true');
			header('Content-type: text/css');
			header('ETag: "' . $etag . '"');
			if ($etag == $noneMatch) {
				header($protocol . ' 304 Not Modified');

				return;
			}
			$modifiedSince = $this->getIfModifiedSinceHeader();
			$mtime         = filemtime($output);
			if (@strtotime($modifiedSince) == $mtime) {
				header($protocol . ' 304 Not Modified');

				return;
			}
			$lastModified = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';
			header('Last-Modified: ' . $lastModified);
			echo file_get_contents($output);

			return;
		}
		header($protocol . ' 404 Not Found');
		header('Content-type: text/plain');
		$v = titanscssc::$VERSION;
		echo "/* INPUT NOT FOUND scss $v */\n";
	}

	/**
	 * Get path to requested .scss file
	 *
	 * @return string
	 */
	protected function findInput() {
		if (($input = $this->inputName())
		    && strpos($input, '..') == FALSE
		    && substr($input, -5) == '.scss'
		) {
			$name = $this->join($this->dir, $input);
			if (is_file($name) && is_readable($name)) {
				return $name;
			}
		}

		return FALSE;
	}

	/**
	 * Get name of requested .scss file
	 *
	 * @return string|null
	 */
	protected function inputName() {
		switch (TRUE) {
			case isset($_GET['p']):
				return $_GET['p'];
			case isset($_SERVER['PATH_INFO']):
				return $_SERVER['PATH_INFO'];
			case isset($_SERVER['DOCUMENT_URI']):
				return substr($_SERVER['DOCUMENT_URI'], strlen($_SERVER['SCRIPT_NAME']));
		}
	}

	/**
	 * Get path to cached .css file
	 *
	 * @return string
	 */
	protected function cacheName($fname) {
		return $this->join($this->cacheDir, md5($fname) . '.css');
	}

	/**
	 * Get If-None-Match header from client request
	 *
	 * @return string|null
	 */
	protected function getIfNoneMatchHeader() {
		$noneMatch = NULL;
		if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
			$noneMatch = $_SERVER['HTTP_IF_NONE_MATCH'];
		}

		return $noneMatch;
	}

	/**
	 * Determine whether .scss file needs to be re-compiled.
	 *
	 * @param string $in   Input path
	 * @param string $out  Output path
	 * @param string $etag ETag
	 *
	 * @return boolean True if compile required.
	 */
	protected function needsCompile($in, $out, &$etag) {
		if (!is_file($out)) {
			return TRUE;
		}
		$mtime = filemtime($out);
		if (filemtime($in) > $mtime) {
			return TRUE;
		}
		$metadataName = $this->metadataName($out);
		if (is_readable($metadataName)) {
			$metadata = unserialize(file_get_contents($metadataName));
			if ($metadata['etag'] == $etag) {
				return FALSE;
			}
			foreach ($metadata['imports'] as $import) {
				if (filemtime($import) > $mtime) {
					return TRUE;
				}
			}
			$etag = $metadata['etag'];

			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Get path to meta data
	 *
	 * @return string
	 */
	protected function metadataName($out) {
		return $out . '.meta';
	}

	/**
	 * Compile .scss file
	 *
	 * @param string $in  Input path (.scss)
	 * @param string $out Output path (.css)
	 *
	 * @return array
	 */
	protected function compile($in, $out) {
		$start   = microtime(TRUE);
		$css     = $this->scss->compile(file_get_contents($in), $in);
		$elapsed = round((microtime(TRUE) - $start), 4);
		$v    = titanscssc::$VERSION;
		$t    = @date('r');
		$css  = "/* compiled by scssphp $v on $t (${elapsed}s) */\n\n" . $css;
		$etag = md5($css);
		file_put_contents($out, $css);
		file_put_contents(
			$this->metadataName($out),
			serialize([
				          'etag'    => $etag,
				          'imports' => $this->scss->getParsedFiles(),
			          ])
		);

		return [$css, $etag];
	}

	/**
	 * Get If-Modified-Since header from client request
	 *
	 * @return string|null
	 */
	protected function getIfModifiedSinceHeader() {
		$modifiedSince = NULL;
		if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
			$modifiedSince = $_SERVER['HTTP_IF_MODIFIED_SINCE'];
			if (FALSE != ($semicolonPos = strpos($modifiedSince, ';'))) {
				$modifiedSince = substr($modifiedSince, 0, $semicolonPos);
			}
		}

		return $modifiedSince;
	}
}
