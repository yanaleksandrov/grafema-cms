<?php
namespace Grafema;

/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE.md
 */
class Html
{
    private $options;

    private $pos;

    private $token;

    private $current_mode;

    private $tags;

    private $tag_type;

    private $token_text;

    private $last_token;

    private $last_text;

    private $token_type;

    private $newlines;

    private $indent_content;

    private $indent_level;

    private $line_char_count;

    private $indent_string;

    private $whitespace = ["\n", "\r", "\t", ' '];

    // all the single tags for HTML
    private $single_token = [
        'br',
        'input',
        'link',
        'meta',
        '!doctype',
        'basefont',
        'base',
        'area',
        'hr',
        'wbr',
        'param',
        'img',
        'source',
        'isindex',
        '?xml',
        'embed',
        '?php',
        '?',
        '?=',
    ];

    // for tags that need a line of whitespace before them, e.g. [ 'head', 'body', '/html' ]
    private $extra_liners = [];

    public function __construct($options = [], $css_beautify = null, $js_beautify = null)
    {
        $this->set_options($options);

        $this->css_beautify = ($css_beautify && is_callable($css_beautify)) ? $css_beautify : false;
        $this->js_beautify = ($js_beautify && is_callable($js_beautify)) ? $js_beautify : false;

        $this->pos = 0; // Parser position
        $this->token = '';
        $this->current_mode = 'CONTENT'; // reflects the current Parser mode: TAG/CONTENT

        // An object to hold tags, their position, and their parent-tags, initiated with default values
        $this->tags = [
            'parent'      => 'parent1',
            'parentcount' => 1,
            'parent1'     => '',
        ];

        $this->tag_type = '';
        $this->token_text = $this->last_token = $this->last_text = $this->token_type = '';
        $this->newlines = 0;

        $this->indent_content = $this->options['indent_inner_html'];
        $this->indent_level = 0;
        $this->line_char_count = 0; // count to see if wrap_line_length was exceeded
        $this->indent_string = str_repeat($this->options['indent_char'], $this->options['indent_size']);
    }

    public function set_options($options)
    {
        $this->options['indent_inner_html'] = boolval($options['indent_inner_html'] ?? false);
        $this->options['indent_size'] = intval($options['indent_size'] ?? 4);
        $this->options['indent_char'] = strval($options['indent_char'] ?? ' ');
        $this->options['wrap_line_length'] = intval($options['wrap_line_length'] ?? 32786);
        $this->options['unformatted'] = ! empty($options['unformatted']) && is_array($options['unformatted']) ? $options['unformatted'] : ['code', 'pre', 'strong', 'textarea'];
        $this->options['preserve_newlines'] = boolval($options['preserve_newlines'] ?? true);
        $this->options['max_preserve_newlines'] = $this->options['preserve_newlines'] && isset($options['max_preserve_newlines']) ? (int) $options['max_preserve_newlines'] : 32786;

        if (isset($options['indent_scripts']) && in_array($options['indent_scripts'], ['keep', 'separate', 'normal'], true)) {
            $this->options['indent_scripts'] = $options['indent_scripts'];
        } else {
            $this->options['indent_scripts'] = 'normal';
        }
    }

    public function minify($input)
    {
        return preg_replace(
            [
                '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
                '/[^\S ]+\</s',     // strip whitespaces before tags, except space
                '/(\s)+/s',         // shorten multiple whitespace sequences
                '/<!--(.|\s)*?-->/', // Remove HTML comments
            ],
            [
                '>',
                '<',
                '\\1',
                '',
            ],
            $input
        );
    }

    public function beautify($input)
    {
        $this->input = $input; // gets the input for the Parser
        $this->input_length = strlen($this->input);
        $this->output = [];

        while (true) {
            $t = $this->get_token();

            $this->token_text = $t[0];
            $this->token_type = $t[1];

            if ($this->token_type === 'TK_EOF') {
                break;
            }

            switch ($this->token_type) {
                case 'TK_TAG_START':
                    $this->print_newline(false, $this->output);
                    $this->print_token($this->token_text);
                    if ($this->indent_content) {
                        $this->indent();
                        $this->indent_content = false;
                    }
                    $this->current_mode = 'CONTENT';
                    break;
                case 'TK_TAG_STYLE':
                case 'TK_TAG_SCRIPT':
                    $this->print_newline(false, $this->output);
                    $this->print_token($this->token_text);
                    $this->current_mode = 'CONTENT';
                    break;
                case 'TK_TAG_END':
                    // Print new line only if the tag has no content and has child
                    if ($this->last_token === 'TK_CONTENT' && $this->last_text === '') {
                        $skip_tags = false;
                        $matches = [];
                        preg_match('/\w+/', $this->token_text, $matches);
                        $tag_name = $matches[0] ?? null;

                        $tag_extracted_from_last_output = null;
                        if (count($this->output)) {
                            $matches = [];
                            preg_match('/(?:<|{{#)\s*(\w+)/', $this->output[count($this->output) - 1], $matches);
                            $tag_extracted_from_last_output = $matches[0] ?? null;
                        }

                        if ( ! $skip_tags && ($tag_extracted_from_last_output === null || $tag_extracted_from_last_output[1] !== $tag_name)) {
                            $this->print_newline(false, $this->output);
                        }
                    }
                    $this->print_token($this->token_text);
                    $this->current_mode = 'CONTENT';
                    break;
                case 'TK_TAG_SINGLE':
                    // Don't add a newline before elements that should remain unformatted.
                    $matches = [];
                    preg_match('/^\s*<([a-z]+)/i', $this->token_text, $matches);
                    $tag_check = $matches ? $matches : null;

                    if ( ! $tag_check || ! in_array($tag_check[1], $this->options['unformatted'], true)) {
                        $this->print_newline(false, $this->output);
                    }
                    $this->print_token($this->token_text);
                    $this->current_mode = 'CONTENT';
                    break;
                case 'TK_CONTENT':
                    $this->print_token($this->token_text);
                    $this->current_mode = 'TAG';
                    break;
                case 'TK_STYLE':
                case 'TK_SCRIPT':
                    if ($this->token_text !== '') {
                        $this->print_newline(false, $this->output);
                        $text = $this->token_text;
                        $_beautifier = false;
                        $script_indent_level = 1;

                        if ($this->token_type === 'TK_SCRIPT') {
                            $_beautifier = $this->js_beautify;
                        } elseif ($this->token_type === 'TK_STYLE') {
                            $_beautifier = $this->css_beautify;
                        }

                        if ($this->options['indent_scripts'] === 'keep') {
                            $script_indent_level = 0;
                        } elseif ($this->options['indent_scripts'] === 'separate') {
                            $script_indent_level = -$this->indent_level;
                        }

                        $indentation = $this->get_full_indent($script_indent_level);
                        if ($_beautifier) {
                            // call the Beautifier if avaliable
                            $text = $_beautifier(preg_replace('/^\s*/', $indentation, $text), $this->options);
                        } else {
                            // simply indent the string otherwise

                            $matches = [];
                            preg_match('/^\s*/', $text, $matches);
                            $white = $matches[0] ?? null;

                            $matches = [];
                            preg_match('/[^\n\r]*$/', $white, $matches);
                            $dummy = $matches[0] ?? null;

                            $_level = count(explode($this->indent_string, $dummy)) - 1;
                            $reindent = $this->get_full_indent($script_indent_level - $_level);

                            $text = preg_replace('/^\s*/', $indentation, $text);
                            $text = preg_replace('/\r\n|\r|\n/', "\n" . $reindent, $text);
                            $text = preg_replace('/\s+$/', '', $text);
                        }

                        if ($text) {
                            $this->print_token_raw($indentation . trim($text));
                            $this->print_newline(false, $this->output);
                        }
                    }
                    $this->current_mode = 'TAG';
                    break;
            }

            $this->last_token = $this->token_type;
            $this->last_text = $this->token_text;
        }

        return implode('', $this->output);
    }

    private function traverse_whitespace()
    {
        $input_char = $this->input[$this->pos] ?? '';
        if ($input_char && in_array($input_char, $this->whitespace, true)) {
            $this->newlines = 0;
            while ($input_char && in_array($input_char, $this->whitespace, true)) {
                if ($this->options['preserve_newlines']
                    && $input_char === "\n"
                    && $this->newlines <= $this->options['max_preserve_newlines']) {
                    ++$this->newlines;
                }

                ++$this->pos;
                $input_char = $this->input[$this->pos] ?? '';
            }
            return true;
        }
        return false;
    }

    // function to capture regular content between tags
    private function get_content()
    {
        $input_char = '';
        $content = [];
        $space = false; // if a space is needed

        while (isset($this->input[$this->pos]) && $this->input[$this->pos] !== '<') {
            if ($this->pos >= $this->input_length) {
                return count($content) ? implode('', $content) : ['', 'TK_EOF'];
            }

            if ($this->traverse_whitespace()) {
                if (count($content)) {
                    $space = true;
                }
                continue; // don't want to insert unnecessary space
            }

            $input_char = $this->input[$this->pos];
            ++$this->pos;

            if ($space) {
                if ($this->line_char_count >= $this->options['wrap_line_length']) { // insert a line when the wrap_line_length is reached
                    $this->print_newline(false, $content);
                    $this->print_indentation($content);
                } else {
                    ++$this->line_char_count;
                    $content[] = ' ';
                }
                $space = false;
            }
            ++$this->line_char_count;
            $content[] = $input_char; // letter at-a-time (or string) inserted to an array
        }

        return count($content) ? implode('', $content) : '';
    }

    // get the full content of a script or style to pass to js_beautify
    private function get_contents_to($name)
    {
        if ($this->pos === $this->input_length) {
            return ['', 'TK_EOF'];
        }
        $input_char = '';
        $content = '';

        $reg_array = [];
        preg_match('#</' . preg_quote($name, '#') . '\\s*>#im', $this->input, $reg_array, PREG_OFFSET_CAPTURE, $this->pos);
        $end_script = $reg_array ? ($reg_array[0][1]) : $this->input_length; // absolute end of script

        if ($this->pos < $end_script) { // get everything in between the script tags
            $content = substr($this->input, $this->pos, max($end_script - $this->pos, 0));
            $this->pos = $end_script;
        }

        return $content;
    }

    // function to record a tag and its parent in this.tags Object
    private function record_tag($tag)
    {
        if (isset($this->tags[$tag . 'count'])) { // check for the existence of this tag type
            ++$this->tags[$tag . 'count'];
            $this->tags[$tag . $this->tags[$tag . 'count']] = $this->indent_level; // and record the present indent level
        } else { // otherwise initialize this tag type
            $this->tags[$tag . 'count'] = 1;
            $this->tags[$tag . $this->tags[$tag . 'count']] = $this->indent_level; // and record the present indent level
        }
        $this->tags[$tag . $this->tags[$tag . 'count'] . 'parent'] = $this->tags['parent']; // set the parent (i.e. in the case of a div this.tags.div1parent)
        $this->tags['parent'] = $tag . $this->tags[$tag . 'count']; // and make this the current parent (i.e. in the case of a div 'div1')
    }

    // function to retrieve the opening tag to the corresponding closer
    private function retrieve_tag($tag)
    {
        if (isset($this->tags[$tag . 'count'])) { // if the openener is not in the Object we ignore it
            $temp_parent = $this->tags['parent']; // check to see if it's a closable tag.
            while ($temp_parent) { // till we reach '' (the initial value);
                if ($tag . $this->tags[$tag . 'count'] === $temp_parent) { // if this is it use it
                    break;
                }
                $temp_parent = $this->tags[$temp_parent . 'parent'] ?? ''; // otherwise keep on climbing up the DOM Tree
            }
            if ($temp_parent) { // if we caught something
                $this->indent_level = $this->tags[$tag . $this->tags[$tag . 'count']]; // set the indent_level accordingly
                $this->tags['parent'] = $this->tags[$temp_parent . 'parent']; // and set the current parent
            }
            unset($this->tags[$tag . $this->tags[$tag . 'count'] . 'parent'] , $this->tags[$tag . $this->tags[$tag . 'count']]); // delete the closed tags parent reference...
            // ...and the tag itself
            if ($this->tags[$tag . 'count'] === 1) {
                unset($this->tags[$tag . 'count']);
            } else {
                --$this->tags[$tag . 'count'];
            }
        }
    }

    private function indent_to_tag($tag)
    {
        // Match the indentation level to the last use of this tag, but don't remove it.
        if ( ! $this->tags[$tag . 'count']) {
            return;
        }
        $temp_parent = $this->tags['parent'];
        while ($temp_parent) {
            if ($tag . $this->tags[$tag . 'count'] === $temp_parent) {
                break;
            }
            $temp_parent = $this->tags[$temp_parent . 'parent'];
        }
        if ($temp_parent) {
            $this->indent_level = $this->tags[$tag . $this->tags[$tag . 'count']];
        }
    }

    // function to get a full tag and parse its type
    private function get_tag($peek = false)
    {
        $input_char = '';
        $content = [];
        $comment = '';
        $space = false;

        $tag_start_char = false;
        $orig_pos = $this->pos;
        $orig_line_char_count = $this->line_char_count;

        do {
            if ($this->pos >= $this->input_length) {
                if ($peek) {
                    $this->pos = $orig_pos;
                    $this->line_char_count = $orig_line_char_count;
                }
                return count($content) ? implode('', $content) : ['', 'TK_EOF'];
            }

            $input_char = $this->input[$this->pos];
            ++$this->pos;

            if (in_array($input_char, $this->whitespace, true)) { // don't want to insert unnecessary space
                $space = true;
                continue;
            }

            if ($input_char === "'" || $input_char === '"') {
                $input_char .= $this->get_unformatted($input_char);
                $space = true;
            }

            if ($input_char === '=') { // no space before =
                $space = false;
            }

            if (count($content) && $content[count($content) - 1] !== '=' && $input_char !== '>' && $space) {
                // no space after = or before >
                if ($this->line_char_count >= $this->options['wrap_line_length']) {
                    $this->print_newline(false, $content);
                    $this->print_indentation($content);
                } else {
                    $content[] = ' ';
                    ++$this->line_char_count;
                }
                $space = false;
            }

            if ($input_char === '<' && ! $tag_start_char) {
                $tag_start = $this->pos - 1;
                $tag_start_char = '<';
            }

            ++$this->line_char_count;
            $content[] = $input_char; // inserts character at-a-time (or string)

            if (isset($content[1]) && $content[1] === '!') { // if we're in a comment, do something special
                // We treat all comments as literals, even more than preformatted tags
                // we just look for the appropriate close tag
                $content = [$this->get_comment($tag_start)];
                break;
            }
        } while ($input_char !== '>');

        $tag_complete = implode('', $content);

        if (str_contains($tag_complete, ' ')) { // if there's whitespace, thats where the tag name ends
            $tag_index = strpos($tag_complete, ' ');
        } else { // otherwise go with the tag ending
            $tag_index = strpos($tag_complete, '>');
        }
        if ($tag_complete[0] === '<') {
            $tag_offset = 1;
        } else {
            $tag_offset = $tag_complete[2] === '#' ? 3 : 2;
        }
        $tag_check = strtolower(substr($tag_complete, $tag_offset, max($tag_index - $tag_offset, 0)));

        if ($tag_complete[strlen($tag_complete) - 2] === '/'
            || in_array($tag_check, $this->single_token, true)) { // if this tag name is a single tag type (either in the list or has a closing /)
            if ( ! $peek) {
                $this->tag_type = 'SINGLE';
            }
        } elseif ($tag_check === 'script') {
            if ( ! $peek) {
                $this->record_tag($tag_check);
                $this->tag_type = 'SCRIPT';
            }
        } elseif ($tag_check === 'style') {
            if ( ! $peek) {
                $this->record_tag($tag_check);
                $this->tag_type = 'STYLE';
            }
        } elseif ($this->is_unformatted($tag_check)) { // do not reformat the "unformatted" tags
            $comment = $this->get_unformatted('</' . $tag_check . '>', $tag_complete); // ...delegate to get_unformatted function

            $content[] = $comment;

            // Preserve collapsed whitespace either before or after this tag.
            if ($tag_start > 0 && in_array($this->input[$tag_start - 1], $this->whitespace, true)) {
                array_splice($content, 0, 0, $this->input[$tag_start - 1]);
            }
            $tag_end = $this->pos - 1;
            if (in_array($this->input[$tag_end + 1], $this->whitespace, true)) {
                $content[] = $this->input[$tag_end + 1];
            }
            $this->tag_type = 'SINGLE';
        } elseif ($tag_check && $tag_check[0] === '!') { // peek for <! comment
            // for comments content is already correct.
            if ( ! $peek) {
                $this->tag_type = 'SINGLE';
                $this->traverse_whitespace();
            }
        } elseif ( ! $peek) {
            if ($tag_check && $tag_check[0] === '/') { // this tag is a double tag so check for tag-ending
                $this->retrieve_tag(substr($tag_check, 1)); // remove it and all ancestors
                $this->tag_type = 'END';
                $this->traverse_whitespace();
            } else { // otherwise it's a start-tag
                $this->record_tag($tag_check); // push it on the tag stack
                if (strtolower($tag_check) !== 'html') {
                    $this->indent_content = true;
                }
                $this->tag_type = 'START';

                // Allow preserving of newlines after a start tag
                $this->traverse_whitespace();
            }
            if (in_array($tag_check, $this->extra_liners, true)) { // check if this double needs an extra line
                $this->print_newline(false, $this->output);
                if (count($this->output) && $this->output[count($this->output) - 2] !== "\n") {
                    $this->print_newline(true, $this->output);
                }
            }
        }

        if ($peek) {
            $this->pos = $orig_pos;
            $this->line_char_count = $orig_line_char_count;
        }

        return implode('', $content); // returns fully formatted tag
    }

    // function to return comment content in its entirety
    private function get_comment($start_pos)
    {
        // this is very poor perf, but will work for now.
        $comment = '';
        $delimiter = '>';
        $matched = false;

        $this->pos = $start_pos;
        $input_char = $this->input[$this->pos];
        ++$this->pos;

        while ($this->pos <= $this->input_length) {
            $comment .= $input_char;

            // only need to check for the delimiter if the last chars match
            if ($comment[strlen($comment) - 1] === $delimiter[strlen($delimiter) - 1]
                && strpos($comment, $delimiter) !== false) {
                break;
            }

            // only need to search for custom delimiter for the first few characters
            if ( ! $matched && strlen($comment) < 10) {
                if (strpos($comment, '<![if') === 0) { // peek for <![if conditional comment
                    $delimiter = '<![endif]>';
                    $matched = true;
                } elseif (strpos($comment, '<![cdata[') === 0) { // if it's a <[cdata[ comment...
                    $delimiter = ']]>';
                    $matched = true;
                } elseif (strpos($comment, '<![') === 0) { // some other ![ comment? ...
                    $delimiter = ']>';
                    $matched = true;
                } elseif (strpos($comment, '<!--') === 0) { // <!-- comment ...
                    $delimiter = '-->';
                    $matched = true;
                }
            }

            $input_char = $this->input[$this->pos];
            ++$this->pos;
        }

        return $comment;
    }

    // function to return unformatted content in its entirety
    private function get_unformatted($delimiter, $orig_tag = false)
    {
        if ($orig_tag && strpos(strtolower($orig_tag), $delimiter) !== false) {
            return '';
        }

        $content = '';
        $min_index = 0;
        $space = true;

        do {
            if ($this->pos >= $this->input_length) {
                return $content;
            }

            $input_char = $this->input[$this->pos];
            ++$this->pos;

            if (in_array($input_char, $this->whitespace, true)) {
                if ( ! $space) {
                    --$this->line_char_count;
                    continue;
                }
                if ($input_char === "\n" || $input_char === "\r") {
                    $content .= "\n";
                    $this->line_char_count = 0;
                    continue;
                }
            }
            $content .= $input_char;
            ++$this->line_char_count;
            $space = true;

            /*
             * Assuming Base64 This method could possibly be applied to All Tags
             * but Base64 doesn't have " or ' as part of its data
             * so it is safe to look for the Next delimiter to find the end of the data
             * instead of reading Each character one at a time.
             */
            if (preg_match('/^data:image\/(bmp|gif|jpeg|png|svg\+xml|tiff|x-icon);base64$/', $content)) {
                $content .= substr($this->input, $this->pos, strpos($this->input, $delimiter, $this->pos) - $this->pos);

                $this->line_char_count = strpos($this->input, $delimiter, $this->pos) - $this->pos;

                $this->pos = strpos($this->input, $delimiter, $this->pos);
            }
        } while (strpos(strtolower($content), $delimiter, $min_index) === false);

        return $content;
    }

    // initial handler for token-retrieval
    private function get_token()
    {
        if ($this->last_token === 'TK_TAG_SCRIPT' || $this->last_token === 'TK_TAG_STYLE') { // check if we need to format javascript
            $type = substr($this->last_token, 7);
            $token = $this->get_contents_to($type);
            if ( ! is_string($token)) {
                return $token;
            }
            return [$token, 'TK_' . $type];
        }
        if ($this->current_mode === 'CONTENT') {
            $token = $this->get_content();
            if ( ! is_string($token)) {
                return $token;
            }
            return [$token, 'TK_CONTENT'];
        }

        if ($this->current_mode === 'TAG') {
            $token = $this->get_tag();

            if ( ! is_string($token)) {
                return $token;
            }
            $tag_name_type = 'TK_TAG_' . $this->tag_type;
            return [$token, $tag_name_type];
        }
    }

    private function get_full_indent($level)
    {
        $level = $this->indent_level + $level || 0;
        if ($level < 1) {
            return '';
        }

        return str_repeat($this->indent_string, $level);
    }

    private function is_unformatted($tag_check)
    {
        // is this an HTML5 block-level link?
        if ( ! in_array($tag_check, $this->options['unformatted'], true)) {
            return false;
        }

        if (strtolower($tag_check) !== 'a' || ! in_array('a', $this->options['unformatted'], true)) {
            return true;
        }

        // at this point we have a tag; is its first child something we want to remain unformatted?
        $next_tag = $this->get_tag(true /* peek. */);

        // test next_tag to see if it is just html tag (no external content)
        $matches = [];
        preg_match('/^\s*<\s*\/?([a-z]*)\s*[^>]*>\s*$/',  $next_tag ? $next_tag : '' , $matches);
        $tag = $matches ? $matches : null;

        // if next_tag comes back but is not an isolated tag, then
        // let's treat the 'a' tag as having content
        // and respect the unformatted option
        if ( ! $tag || in_array($tag, $this->options['unformatted'], true)) {
            return true;
        }
        return false;
    }

    private function print_newline($force, &$arr)
    {
        $this->line_char_count = 0;
        if ( ! $arr || ! count($arr)) {
            return;
        }
        if ($force || ($arr[count($arr) - 1] !== "\n")) { // we might want the extra line
            $arr[] = "\n";
        }
    }

    private function print_indentation(&$arr)
    {
        for ($i = 0; $i < $this->indent_level; ++$i) {
            $arr[] = $this->indent_string;
            $this->line_char_count += strlen($this->indent_string);
        }
    }

    private function print_token($text)
    {
        if ($text !== '') {
            if (count($this->output) && $this->output[count($this->output) - 1] === "\n") {
                $this->print_indentation($this->output);
                $text = ltrim($text);
            }
        }
        $this->print_token_raw($text);
    }

    private function print_token_raw($text)
    {
        if ($text !== '') {
            if (strlen($text) > 1 && $text[strlen($text) - 1] === "\n") {
                // unformatted tags can grab newlines as their last character
                $this->output[] = substr($text, 0, -1);
                $this->print_newline(false, $this->output);
            } else {
                $this->output[] = $text;
            }
        }

        for ($n = 0; $n < $this->newlines; ++$n) {
            $this->print_newline($n > 0, $this->output);
        }
        $this->newlines = 0;
    }

    private function indent()
    {
        ++$this->indent_level;
    }

    private function unindent()
    {
        if ($this->indent_level > 0) {
            --$this->indent_level;
        }
    }
}
