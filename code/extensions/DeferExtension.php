<?php


class MyBackend extends Requirements_Backend
{
    /**
     * Update the given HTML content with the appropriate include tags for the registered
     * requirements. Needs to receive a valid HTML/XHTML template in the $content parameter,
     * including a head and body tag.
     *
     * @param string $templateFile No longer used, only retained for compatibility
     * @param string $content      HTML content that has already been parsed from the $templateFile
     *                             through {@link SSViewer}
     * @return string HTML content augmented with the requirements tags
     */
    public function includeInHTML($templateFile, $content)
    {

        if (
            (strpos($content, '</head>') !== false || strpos($content, '</head ') !== false)
            && ($this->css || $this->javascript || $this->customCSS || $this->customScript || $this->customHeadTags)
        ) {
            $requirements   = '';
            $jsRequirements = '';

            // Combine files - updates $this->javascript and $this->css
            $this->process_combined_files();

            foreach (array_diff_key($this->javascript, $this->blocked) as $file => $dummy) {
                $path = Convert::raw2xml($this->path_for_file($file));
                if ($path) {
                    $jsRequirements .= "<script defer type=\"text/javascript\" src=\"$path\"></script>\n";
                }
            }

            // Add all inline JavaScript *after* including external files they might rely on
            if ($this->customScript) {
                foreach (array_diff_key($this->customScript, $this->blocked) as $script) {
                    $jsRequirements .= "<script type=\"text/javascript\">\n//<![CDATA[\n";
                    $jsRequirements .= "$script\n";
                    $jsRequirements .= "\n//]]>\n</script>\n";
                }
            }

            foreach (array_diff_key($this->css, $this->blocked) as $file => $params) {
                $path = Convert::raw2xml($this->path_for_file($file));
                if ($path) {
                    $media        = (isset($params['media']) && !empty($params['media']))
                        ? " media=\"{$params['media']}\"" : "";
                    $requirements .= "<link rel=\"stylesheet\" type=\"text/css\"{$media} href=\"$path\" />\n";
                }
            }

            foreach (array_diff_key($this->customCSS, $this->blocked) as $css) {
                $requirements .= "<style type=\"text/css\">\n$css\n</style>\n";
            }

            foreach (array_diff_key($this->customHeadTags, $this->blocked) as $customHeadTag) {
                $requirements .= "$customHeadTag\n";
            }

            $replacements = [];
            if ($this->force_js_to_bottom) {
                $jsRequirements = $this->removeNewlinesFromCode($jsRequirements);

                // Forcefully put the scripts at the bottom of the body instead of before the first
                // script tag.
                $replacements["/(<\/body[^>]*>)/i"] = $this->escapeReplacement($jsRequirements) . "\\1";

                // Put CSS at the bottom of the head
                $replacements["/(<\/head>)/i"] = $this->escapeReplacement($requirements) . "\\1";
            } elseif ($this->write_js_to_body) {
                $jsRequirements = $this->removeNewlinesFromCode($jsRequirements);

                // If your template already has script tags in the body, then we try to put our script
                // tags just before those. Otherwise, we put it at the bottom.
                $p2 = stripos($content, '<body');
                $p1 = stripos($content, '<script', $p2);

                $commentTags    = [];
                $canWriteToBody = ($p1 !== false)
                                  &&
                                  // Check that the script tag is not inside a html comment tag
                                  !(
                                      preg_match('/.*(?|(<!--)|(-->))/U', $content, $commentTags, 0, $p1)
                                      &&
                                      $commentTags[1] == '-->'
                                  );

                if ($canWriteToBody) {
                    $content = substr($content, 0, $p1) . $jsRequirements . substr($content, $p1);
                } else {
                    $replacements["/(<\/body[^>]*>)/i"] = $this->escapeReplacement($jsRequirements) . "\\1";
                }

                // Put CSS at the bottom of the head
                $replacements["/(<\/head>)/i"] = $this->escapeReplacement($requirements) . "\\1";
            } else {
                // Put CSS and Javascript together before the closing head tag
                $replacements["/(<\/head>)/i"] = $this->escapeReplacement($requirements . $jsRequirements) . "\\1";
            }

            if (!empty($replacements)) {
                // Replace everything at once (only once)
                $content = preg_replace(array_keys($replacements), array_values($replacements), $content, 1);
            }
        }

        return $content;
    }
}
