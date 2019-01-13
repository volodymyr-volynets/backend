<?php

namespace Numbers\Backend\Db\Oracle\Model\Function2;
class ConcatWs extends \Object\Function2 {
	public $db_link;
	public $db_link_flag;
	public $schema;
	public $name = 'concat_ws';
	public $backend = 'Oracle';
	public $header = 'public2.concat_ws(separator in varchar2, s1 in varchar2, s2 in varchar2 default NULL, s3 in varchar2 default NULL, s4 in varchar2 default NULL, s5 in varchar2 default NULL, s6 in varchar2 default NULL, s7 in varchar2 default NULL, s8 in varchar2 default NULL, s9 in varchar2 default NULL, s10 in varchar2 default NULL, s11 in varchar2 default NULL, s12 in varchar2 default NULL, s13 in varchar2 default NULL, s14 in varchar2 default NULL, s15 in varchar2 default NULL, s16 in varchar2 default NULL, s17 in varchar2 default NULL, s18 in varchar2 default NULL, s19 in varchar2 default NULL, s20 in varchar2 default NULL) RETURN varchar2';
	public $definition = "CREATE OR REPLACE NONEDITIONABLE FUNCTION public2.concat_ws(
    separator in varchar2,
    s1 in varchar2,
    s2 in varchar2 default NULL,
    s3 in varchar2 default NULL,
    s4 in varchar2 default NULL,
    s5 in varchar2 default NULL,
    s6 in varchar2 default NULL,
    s7 in varchar2 default NULL,
    s8 in varchar2 default NULL,
    s9 in varchar2 default NULL,
    s10 in varchar2 default NULL,
    s11 in varchar2 default NULL,
    s12 in varchar2 default NULL,
    s13 in varchar2 default NULL,
    s14 in varchar2 default NULL,
    s15 in varchar2 default NULL,
    s16 in varchar2 default NULL,
    s17 in varchar2 default NULL,
    s18 in varchar2 default NULL,
    s19 in varchar2 default NULL,
    s20 in varchar2 default NULL,
	s21 in varchar2 default NULL,
	s22 in varchar2 default NULL,
	s23 in varchar2 default NULL,
	s24 in varchar2 default NULL,
	s25 in varchar2 default NULL,
	s26 in varchar2 default NULL,
	s27 in varchar2 default NULL,
	s28 in varchar2 default NULL,
	s29 in varchar2 default NULL,
	s30 in varchar2 default NULL
)  RETURN varchar2 IS
   result varchar2(32767) := s1;
BEGIN
    IF result IS NULL THEN result:= ''; END IF;
    IF s2 IS NOT NULL THEN result:= result || separator || s2; END IF;
    IF s3 IS NOT NULL THEN result:= result || separator || s3; END IF;
    IF s4 IS NOT NULL THEN result:= result || separator || s4; END IF;
    IF s5 IS NOT NULL THEN result:= result || separator || s5; END IF;
    IF s6 IS NOT NULL THEN result:= result || separator || s6; END IF;
    IF s7 IS NOT NULL THEN result:= result || separator || s7; END IF;
    IF s8 IS NOT NULL THEN result:= result || separator || s8; END IF;
    IF s9 IS NOT NULL THEN result:= result || separator || s9; END IF;
    IF s10 IS NOT NULL THEN result:= result || separator || s10; END IF;
    IF s11 IS NOT NULL THEN result:= result || separator || s11; END IF;
    IF s12 IS NOT NULL THEN result:= result || separator || s12; END IF;
    IF s13 IS NOT NULL THEN result:= result || separator || s13; END IF;
    IF s14 IS NOT NULL THEN result:= result || separator || s14; END IF;
    IF s15 IS NOT NULL THEN result:= result || separator || s15; END IF;
    IF s16 IS NOT NULL THEN result:= result || separator || s16; END IF;
    IF s17 IS NOT NULL THEN result:= result || separator || s17; END IF;
    IF s18 IS NOT NULL THEN result:= result || separator || s18; END IF;
    IF s19 IS NOT NULL THEN result:= result || separator || s19; END IF;
    IF s20 IS NOT NULL THEN result:= result || separator || s20; END IF;
	IF s21 IS NOT NULL THEN result:= result || separator || s21; END IF;
	IF s22 IS NOT NULL THEN result:= result || separator || s22; END IF;
	IF s23 IS NOT NULL THEN result:= result || separator || s23; END IF;
	IF s24 IS NOT NULL THEN result:= result || separator || s24; END IF;
	IF s25 IS NOT NULL THEN result:= result || separator || s25; END IF;
	IF s26 IS NOT NULL THEN result:= result || separator || s26; END IF;
	IF s27 IS NOT NULL THEN result:= result || separator || s27; END IF;
	IF s28 IS NOT NULL THEN result:= result || separator || s28; END IF;
	IF s29 IS NOT NULL THEN result:= result || separator || s29; END IF;
	IF s30 IS NOT NULL THEN result:= result || separator || s30; END IF;
    RETURN result;
END;";
}