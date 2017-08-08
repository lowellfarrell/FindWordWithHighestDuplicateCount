<?php

namespace detector;

/**
 * DuplicateDector class
 *
 */
class DuplicateDector
{

    /**
     * The contractor requires that a logger be passed into it
     * @param \detector\logger\LoggerHelper $logger
     */
    public function __construct(logger\LoggerHelper $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Search a given string to find the word with the greatest amout of
     *  duplication of a given letter a-z.
     * 
     * @param string $text The text to searched
     * @return string The word with the highest level of duplication of a single 
     * character a-z.  If no word is found with duplication then an empty string 
     * is returned.
     */
    public function findHighestDuplicationPerWordInText($text)
    {
        $this->logger->log("Searching through the following text: \r\n " . $text);

        $result = '';
        $highestDuplicationLevelForText = 1;
        $wordArray = $this->createWordArrayFromText((string) $text);
        foreach ($wordArray as $word) {
            $alphaChars = '/[^a-zA-Z]+/';
            $wordSrubbed = preg_replace($alphaChars, "", $word);
            $wordSrubbedLower = strtolower($wordSrubbed);
            $counts = count_chars($wordSrubbedLower, 1);
            arsort($counts);
            $hisghestDupCount = reset($counts);
            if ($hisghestDupCount !== false && (int) $hisghestDupCount > $highestDuplicationLevelForText) {
                $result = $word;
                $highestDuplicationLevelForText = (int) $hisghestDupCount;
            }
        }
        return $result;
    }

    /**
     * Converts text into an array of words.
     * 
     * @param String $text
     * @return array An array of words will be returned.
     */
    private function createWordArrayFromText($text)
    {
        return preg_split('/\s+/', $text);
    }

}
