<?php
function getFlags(string $countryCode): string
{
    return (string) preg_replace_callback(
        '/./',
        static fn (array $letter) => mb_chr(ord($letter[0]) % 32 + 0x1F1E5),
        $countryCode
    );
}
?>
