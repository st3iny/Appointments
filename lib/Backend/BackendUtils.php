<?php
/** @noinspection PhpUndefinedFieldInspection */

/** @noinspection PhpMissingParamTypeInspection */
/** @noinspection PhpMissingReturnTypeInspection */
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpFullyQualifiedNameUsageInspection */
/** @noinspection PhpComposerExtensionStubsInspection */


namespace OCA\Appointments\Backend;

use OCA\Appointments\AppInfo\Application;
use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IConfig;
use OCP\IDBConnection;
use OCP\IL10N;
use OCP\IURLGenerator;
use Psr\Log\LoggerInterface;
use Sabre\VObject\Reader;

class BackendUtils
{

    const APPT_CAT = "Appointment";
    const TZI_PROP = "X-TZI";
    const XAD_PROP = "X-APPT-DATA";
    // original description
    const X_DSR = "X-APPT-DSR";

    // #################################################################
    //   WARNING: most of constants are used in V2 migration
    //   ******** DO NOT CHANGE/DELETE (yet) ********
    // #################################################################
    const CIPHER = "AES-128-CFB";
    const HASH_TABLE_NAME = "appointments_hash";
    const PREF_TABLE_NAME = "appointments_pref";
    const PREF_TABLE_V2_NAME = "appointments_pref_v2";
    const SYNC_TABLE_NAME = "appointments_sync";

    public const KEY_USE_DEF_EMAIL = 'useDefaultEmail';
    public const KEY_LIMIT_TO_GROUPS = 'limitToGroups';
    public const KEY_EMAIL_FIX = 'emailFixOpt';
    public const KEY_ORG = 'org_info';
    public const KEY_USER_ID = "user_id";
    public const KEY_TOKEN = "token";
    public const KEY_PAGE_ID = "page_id"; // this can be 'p0','p1',... or 'd0'(DIR_PAGE_ID) for directory page
    public const KEY_DATA = "data";
    public const KEY_EML = 'email_options';
    public const KEY_CLS = 'calendar_settings';
    public const KEY_TMPL_DATA = 'template_data';
    public const KEY_TMPL_INFO = 'template_info';
    public const KEY_PSN = "page_options";
    public const KEY_MPS_COL = "more_pages";
    public const KEY_MPS = "more_pages_";
    public const KEY_PAGES = "pages";
    public const KEY_DIR = "dir_info";
    public const KEY_TALK = "appt_talk";
    public const KEY_FORM_INPUTS_JSON = 'fi_json';
    public const KEY_FORM_INPUTS_HTML = 'fi_html';
    public const KEY_REMINDERS = "reminders";
    public const KEY_DEBUGGING = "debugging";

    const PREF_STATUS_TENTATIVE = 0;
    const PREF_STATUS_CONFIRMED = 1;
    const PREF_STATUS_CANCELLED = 2;

    const FLOAT_TIME_FORMAT = "Ymd.His";

    public const ORG_NAME = 'organization';
    public const ORG_EMAIL = 'email';
    public const ORG_ADDR = 'address';
    public const ORG_PHONE = 'phone';
    // redirect to url after an appointment is confirmed
    public const ORG_CONFIRMED_RDR_URL = 'confirmedRdrUrl';
    public const ORG_CONFIRMED_RDR_ID = 'confirmedRdrId';
    public const ORG_CONFIRMED_RDR_DATA = 'confirmedRdrData';

    // Email Settings
    public const EML_ICS = 'icsFile';
    public const EML_SKIP_EVS = 'skipEVS';
    public const EML_AMOD = 'attMod';
    public const EML_ADEL = 'attDel';
    public const EML_MREQ = 'meReq';
    public const EML_MCONF = 'meConfirm';
    public const EML_MCNCL = 'meCancel';
    public const EML_VLD_TXT = 'vldNote';
    public const EML_CNF_TXT = 'cnfNote';
    public const EML_ICS_TXT = 'icsNote';

    // Calendar Settings
    // simple mode
    public const CLS_MAIN_ID = 'mainCalId'; // this cal_id now
    public const CLS_DEST_ID = 'destCalId';
    // external mode
    public const CLS_XTM_SRC_ID = 'nrSrcCalId';
    public const CLS_XTM_DST_ID = 'nrDstCalId';
    public const CLS_XTM_PUSH_REC = 'nrPushRec';
    public const CLS_XTM_REQ_CAT = 'nrRequireCat';
    public const CLS_XTM_AUTO_FIX = 'nrAutoFix';
    // template mode
    public const CLS_TMM_DST_ID = 'tmmDstCalId';
    public const CLS_TMM_MORE_CALS = 'tmmMoreCals';
    public const CLS_TMM_SUBSCRIPTIONS = 'tmmSubscriptions';
    public const CLS_TMM_SUBSCRIPTIONS_SYNC = 'tmmSubscriptionsSync';
    // --
    // this is global prep time (Minimum lead time)
    public const CLS_PREP_TIME = 'prepTime';

    // per-appointment block times for pending or booked appointmets
    public const CLS_BUFFER_BEFORE = 'bufferBefore';
    public const CLS_BUFFER_AFTER = 'bufferAfter';

    public const CLS_ON_CANCEL = 'whenCanceled';
    public const CLS_ALL_DAY_BLOCK = 'allDayBlock';
    public const CLS_TITLE_TEMPLATE = 'titleTemplate';
    public const CLS_PRIVATE_PAGE = 'privatePage';
    public const CLS_TS_MODE = 'tsMode';
    // values for tsMode
    public const CLS_TS_MODE_SIMPLE = '0';
    public const CLS_TS_MODE_EXTERNAL = '1';
    public const CLS_TS_MODE_TEMPLATE = '2';

    public const TMPL_TZ_NAME = "tzName";
    public const TMPL_TZ_DATA = "tzData";

    public const PSN_PAGE_TITLE = "pageTitle";
    public const PSN_FNED = "startFNED";
    public const PSN_PAGE_STYLE = "pageStyle";
    public const PSN_GDPR = "gdpr";
    public const PSN_GDPR_NO_CHB = "gdprNoChb";
    public const PSN_FORM_TITLE = "formTitle";
    public const PSN_META_NO_INDEX = "metaNoIndex";
    public const PSN_EMPTY = "showEmpty";
    public const PSN_WEEKEND = "showWeekends";
    public const PSN_PAGE_SUB_TITLE = "pageSubTitle";
    public const PSN_NWEEKS = "nbrWeeks";
    public const PSN_TIME2 = "time2Cols";
    public const PSN_HIDE_TEL = "hidePhone";
    public const PSN_END_TIME = "endTime";
    public const PSN_SHOW_TZ = "showTZ";
    public const PSN_USE_NC_THEME = "useNcTheme";

    public const PAGES_ENABLED = "enabled";
    public const PAGES_LABEL = "label";

    public const PAGES_VAL_DEF = array(
        self::PAGES_ENABLED => 0,
        self::PAGES_LABEL => ""
    );

    public const TALK_ENABLED = "talk_enabled";
    public const TALK_DEL_ROOM = "talk_delete";
    public const TALK_EMAIL_TXT = "talk_emailText";
    public const TALK_LOBBY = "talk_lobby";
    public const TALK_PASSWORD = "talk_password";
    public const TALK_NAME_FORMAT = "talk_nameFormat";
    public const TALK_FORM_ENABLED = "talk_formFieldEnable";
    public const TALK_FORM_LABEL = "talk_formLabel";
    public const TALK_FORM_PLACEHOLDER = "talk_formPlaceholder";
    public const TALK_FORM_REAL_TXT = "talk_formTxtReal";
    public const TALK_FORM_VIRTUAL_TXT = "talk_formTxtVirtual";
    public const TALK_FORM_DEF_LABEL = "talk_formDefLabel";
    public const TALK_FORM_DEF_PLACEHOLDER = "talk_formDefPlaceholder";
    public const TALK_FORM_DEF_REAL = "talk_formDefReal";
    public const TALK_FORM_DEF_VIRTUAL = "talk_formDefVirtual";
    public const TALK_FORM_TYPE_CHANGE_TXT = "talk_formTxtTypeChange";
    // if true, Talk Setting are removed from the settings menu
    public const TALK_INTEGRATION_DISABLED = "talk_integration_disabled";

    public const REMINDER_DATA = "data";
    public const REMINDER_DATA_TIME = "seconds";
    public const REMINDER_DATA_ACTIONS = "actions";
    public const REMINDER_SEND_ON_FRIDAY = "friday";
    public const REMINDER_MORE_TEXT = "moreText";
    // Read only background_job_mode from appconfig and overwrite.cli.url from getSystemValue
    public const REMINDER_BJM = "bjm";
    public const REMINDER_CLI_URL = "cliUrl";
    public const REMINDER_LANG = "defaultLang";

    public const DEBUGGING_LOG_REM_BLOCKER = "log_rem_blocker";

    public const DIR_ITEMS = "dirItems";

    public const PAGE_ENABLED = "enabled";
    public const PAGE_LABEL = "label";

    private array|null $settings = null;

    private IConfig $config;
    private IDBConnection $db;
    private IURLGenerator $urlGenerator;
    private IL10N $l10n;
    private LoggerInterface $logger;

    public function __construct(IConfig         $config,
                                IDBConnection   $db,
                                IURLGenerator   $urlGenerator,
                                IL10N           $l10n,
                                LoggerInterface $logger
    ) {
        $this->config = $config;
        $this->db = $db;
        $this->urlGenerator = $urlGenerator;
        $this->l10n = $l10n;
        $this->logger = $logger;
    }

    /**
     * @param \DateTimeImmutable $new_start
     * @param \DateTimeImmutable $new_end
     * @param int $skipped number of skipped recurrences (to adjust the 'COUNT')
     * @param \Sabre\VObject\Component\VCalendar $vo
     */
    function optimizeRecurrence($new_start, $new_end, $skipped, $vo)
    {

        /**  @var \Sabre\VObject\Component\VEvent $evt */
        $evt = $vo->VEVENT;

        $is_floating = $evt->DTSTART->isFloating();

        $evt->DTSTART->setDateTime($new_start, $is_floating);
        // there can be "DURATION" instead of "DTSTART"
        if (isset($evt->DTEND)) {
            // adjust end time
            $evt->DTEND->setDateTime($new_end, $is_floating);
        }

        $this->setSEQ($evt);

        //adjust count if present
        $rra = $evt->RRULE->getParts();
        if (isset($rra['COUNT'])) {
            $rra['COUNT'] -= $skipped;
            $evt->RRULE->setParts($rra);
        }
    }

    /**
     * @param $data
     * @param $info
     * @param $userId
     * @param $uri
     * @return string   Event Data |
     *                  "1"=Bad Status (Most likely booked while waiting),
     *                  "2"=Other Error
     */
    function dataSetAttendee($data, $info, $userId, $uri)
    {

        $vo = Reader::read($data);

        if ($vo === null || !isset($vo->VEVENT)) {
            $this->logger->error("Bad Data: not an event");
            return "2";
        }

        /** @var \Sabre\VObject\Component\VEvent $evt */
        $evt = $vo->VEVENT;

        if (!isset($evt->STATUS) || $evt->STATUS->getValue() !== 'TENTATIVE') {
            $this->logger->error("Bad Status: must be TENTATIVE");
            return "1";
        }

        if (!isset($evt->CATEGORIES) || $evt->CATEGORIES->getValue() !== BackendUtils::APPT_CAT) {
            $this->logger->error("Bad Category: not an " . BackendUtils::APPT_CAT);
            return "2";
        }

        // @see issues #120 and #116
        // Should this be documented ???
        // TODO: this should be $config->getAppValue(...)
        $e_fix = $this->config->getUserValue($userId, Application::APP_ID, self::KEY_EMAIL_FIX);

        if ($e_fix === 'none') {
            $a = $evt->add('ATTENDEE', "mailto:" . $info['email']);
        } elseif ($e_fix === 'scheme') {
            $a = $evt->add('ATTENDEE', "acct:" . $info['email']);
        } else {
            $a = $evt->add('ATTENDEE', "mailto:" . $info['email']);
            $a['SCHEDULE-AGENT'] = "CLIENT";
        }

        $a['CN'] = $info['name'];
        $a['PARTSTAT'] = "NEEDS-ACTION";

        $title = "";
        if (!isset($evt->SUMMARY)) {
            $evt->add('SUMMARY');
        } else {
            $t = $evt->SUMMARY->getValue();
            if ($t[0] === "_") {
                $title = $t;
            }
        }
        $evt->SUMMARY->setValue("⌛ " . $this->makeEvtTitle($userId, $info['name'], $info['_page_id'], $this->getAttendee($evt)->getValue()));

        $dsr = $info['name'] . "\n" . (empty($info['phone']) ? "" : ($info['phone'] . "\n")) . $info['email'] . $info['_more_data'];

        if (isset($info["_more_ics_text"])) {
            // custom ICS text from per settings
            $dsr .= "\n" . $info["_more_ics_text"];
        }
        if (!isset($evt->DESCRIPTION)) {
            $evt->add('DESCRIPTION');
        }
        $evt->DESCRIPTION->setValue($dsr);

        if (!isset($evt->{self::X_DSR})) {
            $evt->add(self::X_DSR);
        }
        $evt->{self::X_DSR}->setValue($dsr);

        if (!isset($evt->STATUS)) {
            $evt->add('STATUS');
        }
        $evt->STATUS->setValue("CONFIRMED");

        if (!isset($evt->TRANSP)) {
            $evt->add('TRANSP');
        }
        $evt->TRANSP->setValue("OPAQUE");

        // Attendee's timezone info at the time of booking
        if (!isset($evt->{self::TZI_PROP})) {
            $evt->add(self::TZI_PROP);
        }
        $evt->{self::TZI_PROP}->setValue($info['tzi']);

        // isset($info['talk_type_real']) === No need for Talk room

        // Additional appointment info (XAD_PROP):
        //  0: userId
        //  1: _title
        //  2: pageId
        //  3: embed (uri)
        //  4: reserved for Talk link @see $this->dataConfirmAttendee()
        //      'd' === add self::TALK_FORM_REAL_TXT to description - no need for Talk room
        //      '_' === check if Talk room is needed
        //      'f' === finished
        //  5: reserved for Talk pass @see $this->dataConfirmAttendee()
        if (!isset($evt->{self::XAD_PROP})) {
            $evt->add(self::XAD_PROP);
        }
        $evt->{self::XAD_PROP}->setValue($this->encrypt(
            $userId . chr(31)
            . $title . chr(31)
            . $info['_page_id'] . chr(31)
            . $info['_embed'] . chr(31)
            // talk link, if isset($info['talk_type_real']) means no need for talk room, @see PageController->showFormPost()
            . (isset($info['talk_type_real']) ? 'd' : '_') . chr(31)
            . '_', // talk pass
            $evt->UID));

        $this->setSEQ($evt);

        $this->setApptHash($evt, $userId, $info['_page_id'], $uri);

        return $vo->serialize();
    }

    /**
     * @param $uri
     * @param $userId
     * @param $noChanges - if true, no changes will be made,  this will only return meeting type
     * @return string[] [new meeting type, '' === error, data]
     */
    function dataChangeApptType($data, $userId, $noChanges = false)
    {
        $r = ['', ''];

        $vo = $this->getAppointment($data, 'CONFIRMED');
        if ($vo === null) {
            return $r;
        }

        /** @var \Sabre\VObject\Component\VEvent $evt */
        $evt = $vo->VEVENT;

        if (isset($evt->{BackendUtils::XAD_PROP})) {
            // @see BackendUtils->dataSetAttendee for BackendUtils::XAD_PROP
            $xad = explode(chr(31), $this->decrypt(
                $evt->{BackendUtils::XAD_PROP}->getValue(),
                $evt->UID->getValue()));

            if (count($xad) > 4) {

                $a = $this->getAttendee($evt);
                if ($a === null) {
                    return $r;
                }

                if ($xad[4] === 'f') {
                    // the appointment was previously finalized as "in-person ..."
                    if ($noChanges) {
                        $settings = $this->getUserSettings();
                        // new_type = virtual
                        $r[0] = (!empty($settings[self::TALK_FORM_VIRTUAL_TXT])
                            ? $settings[self::TALK_FORM_VIRTUAL_TXT]
                            : $settings[self::TALK_FORM_DEF_VIRTUAL]);
                        return $r;
                    }

                    // ... so, set $xad[4]='_' @see BackendUtils->dataSetAttendee
                    // this will add a talk room and description when addTalkInfo is called
                    $xad[4] = '_';

                } elseif (strlen($xad[4]) > 1) {
                    // this was a virtual appointment...
                    // ... $xad[4] is the room token.

                    $settings = $this->getUserSettings();

                    if ($noChanges) {
                        $r[0] = (!empty($settings[self::TALK_FORM_REAL_TXT])
                            ? $settings[self::TALK_FORM_REAL_TXT]
                            : $settings[self::TALK_FORM_DEF_REAL]);
                        return $r;
                    }

                    // delete the room first...
                    $ti = new TalkIntegration($settings, $this);
                    $ti->deleteRoom($xad[4]);

                    // set $xad[4]='d' which will just and description @see BackendUtils->dataSetAttendee
                    $xad[4] = 'd';
                }

                $new_type = $this->addEvtTalkInfo($userId, $xad, $evt, $a);
                $r[0] = $new_type;
                $r[1] = $vo->serialize();
            }
        }

        return $r;
    }

    /**
     * @param $data
     * @param string $userId
     * @return array [string|null, string|null, string|null]
     *                  null=error|""=already confirmed,
     *                  Localized DateTime string
     *                  $pageId
     *                  $attendeeName
     */
    function dataConfirmAttendee($data, $userId)
    {

        $vo = $this->getAppointment($data, 'CONFIRMED');
        if ($vo === null) {
            return [null, null, null, ""];
        }

        /** @var \Sabre\VObject\Component\VEvent $evt */
        $evt = $vo->VEVENT;

        $a = $this->getAttendee($evt);
        if ($a === null) {
            return [null, null, null, ""];
        }

        if (isset($evt->{BackendUtils::XAD_PROP})) {
            // @see BackendUtils->dataSetAttendee for BackendUtils::XAD_PROP
            $xad = explode(chr(31), $this->decrypt(
                $evt->{BackendUtils::XAD_PROP}->getValue(),
                $evt->UID->getValue()));
            if (count($xad) > 2) {
                $pageId = $xad[2];
            } else {
                $pageId = 'p0';
            }
        } else {
            return [null, null, null, ""];
        }

        $dts = $this->getDateTimeString(
            $evt->DTSTART->getDateTime(),
            $evt->{self::TZI_PROP}->getValue()
        );

        $attendeeName = $a->parameters['CN']->getValue();

        if ($a->parameters['PARTSTAT']->getValue() === 'ACCEPTED') {
            return ["", $dts, $pageId, $attendeeName];
        }

        $a->parameters['PARTSTAT']->setValue('ACCEPTED');

        if (!isset($evt->SUMMARY)) {
            $evt->add('SUMMARY');
        } // ???
        $evt->SUMMARY->setValue("✔️ " . $this->makeEvtTitle($userId, $attendeeName, $pageId, $this->getAttendee($evt)->getValue()));

        //Talk link
        $this->addEvtTalkInfo($userId, $xad, $evt, $a);

        $this->setSEQ($evt);

        $this->setApptHash($evt, $xad[0], $pageId);

        return [$vo->serialize(), $dts, $pageId, $attendeeName];
    }

    /**
     * @param $data
     * @param string $userId
     * @return array [string|null, int, string]
     *                  date_time: Localized DateTime string or null on error
     *                  state: one of self::PREF_STATUS_*
     *                  attendeeName: or empty if error
     */
    function dataApptGetInfo($data, $userId)
    {
        $ret = [null, self::PREF_STATUS_TENTATIVE, ""];

        if ($data === null) {
            return $ret;
        }

        $vo = $this->getAppointment($data, '*');
        if ($vo === null) {
            return $ret;
        }

        /** @var \Sabre\VObject\Component\VEvent $evt */
        $evt = $vo->VEVENT;

        $a = $this->getAttendee($evt);
        if ($a === null) {
            return $ret;
        }

        if ($a->parameters['PARTSTAT']->getValue() === 'DECLINED'
            || $evt->STATUS->getValue() === 'CANCELLED') {
            // cancelled
            $ret[1] = self::PREF_STATUS_CANCELLED;
        } else {
            if ($a->parameters['PARTSTAT']->getValue() === 'ACCEPTED') {
                $ret[1] = self::PREF_STATUS_CONFIRMED;
            }
        }

        $ret[0] = $this->getDateTimeString(
            $evt->DTSTART->getDateTime(),
            $evt->{self::TZI_PROP}->getValue()
        );

        // Attendee Name
        $ret[2] = $a->parameters['CN']->getValue();

        return $ret;
    }

    /**
     * @param $userId
     * @param $xad
     * @param $evt
     * @param $a - attendee
     * @return string new appointment type virtual/in-person (from talk settings)
     */
    private function addEvtTalkInfo($userId, $xad, $evt, $a)
    {
        $r = '';

        $settings = $this->getUserSettings();

        if (count($xad) > 4) {
            if ($xad[4] === '_') {
                // check if Talk link is needed
                if ($settings[self::TALK_ENABLED] === true) {
                    $ti = new TalkIntegration($settings, $this);
                    $token = $ti->createRoomForEvent(
                        $a->parameters['CN']->getValue(),
                        $evt->DTSTART,
                        $userId);
                    if (!empty($token)) {

                        $l10n = $this->l10n;
                        if ($token !== "-") {
                            $pi = '';
                            if (strpos($token, chr(31)) === false) {
                                // just token
                                $xad[4] = $token;
                            } else {
                                // taken + pass
                                list($xad[4], $xad[5]) = explode(chr(31), $token);
                                $pi = "\n" . $l10n->t("Guest password:") . " " . $xad[5];
                                $token = $xad[4];
                            }
                            $evt->{self::XAD_PROP}->setValue($this->encrypt(
                                implode(chr(31), $xad), $evt->UID));

                            $this->updateDescription($evt, "\n\n" .
                                $ti->getRoomURL($token) . $pi);

                            $r = (!empty($settings[self::TALK_FORM_VIRTUAL_TXT])
                                ? $settings[self::TALK_FORM_VIRTUAL_TXT]
                                : $settings[self::TALK_FORM_DEF_VIRTUAL]);

                        } else {

                            $this->updateDescription($evt, "\n\n" .
                                $l10n->t("Talk integration error: check logs"));
                        }
                    }
                }
            } elseif ($xad[4] === 'd') {
                // meeting type is overridden by client to real,
                // set xad to 'f' and add self::TALK_FORM_REAL_TXT to description
                $xad[4] = 'f';
                $evt->{self::XAD_PROP}->setValue($this->encrypt(
                    implode(chr(31), $xad), $evt->UID));

                $r = (!empty($settings[self::TALK_FORM_REAL_TXT])
                    ? $settings[self::TALK_FORM_REAL_TXT]
                    : $settings[self::TALK_FORM_DEF_REAL]);

                $this->updateDescription($evt, "\n\n" . $r);
            }
        }

        return $r;
    }

    /**
     * @param \Sabre\VObject\Component\VEvent $evt
     * @param $addString string text to be added to original description
     */
    private function updateDescription($evt, $addString)
    {
        // just in-case
        if (!isset($evt->DESCRIPTION)) {
            $evt->add('DESCRIPTION');
        }

        if (isset($evt->{self::X_DSR})) {
            // we have original description
            $d = $evt->{self::X_DSR}->getValue();
        } else {
            $d = $evt->DESCRIPTION->getValue();
        }

        $evt->DESCRIPTION->setValue($d . $addString);
    }

    /**
     * @param $data
     * @return array [string|null, string|null, string|null]
     *                  null=error|""=already canceled
     *                  Localized DateTime string
     */
    function dataCancelAttendee($data)
    {

        $vo = $this->getAppointment($data, '*');
        if ($vo === null) {
            return [null, null, null];
        }

        /** @var \Sabre\VObject\Component\VEvent $evt */
        $evt = $vo->VEVENT;

        if ($evt->STATUS->getValue() === 'TENTATIVE') {
            // Can not cancel tentative appointments
            return [null, null, null];
        }

        $a = $this->getAttendee($evt);
        if ($a === null) {
            return [null, null, null];
        }

        if (isset($evt->{BackendUtils::XAD_PROP})) {
            // @see BackendUtils->dataSetAttendee for BackendUtils::XAD_PROP
            $xad = explode(chr(31), $this->decrypt(
                $evt->{BackendUtils::XAD_PROP}->getValue(),
                $evt->UID->getValue()));
            if (count($xad) > 2) {
                $pageId = $xad[2];
            } else {
                $pageId = 'p0';
            }
        } else {
            return [null, null, null];
        }

        $dts = $this->getDateTimeString(
            $evt->DTSTART->getDateTime(),
            $evt->{self::TZI_PROP}->getValue()
        );

        if ($a->parameters['PARTSTAT']->getValue() === 'DECLINED'
            || $evt->STATUS->getValue() === 'CANCELLED') {
            // Already cancelled
            return ["", $dts, $pageId];
        }

        $this->evtCancelAttendee($evt);

        $this->setSEQ($evt);

        $this->setApptHash($evt, $xad[0], $pageId);

        return [$vo->serialize(), $dts, $pageId];
    }

    /**
     * This is also called from DavListener
     * @param \Sabre\VObject\Component\VEvent $evt
     * @noinspection PhpParameterByRefIsNotUsedAsReferenceInspection
     */
    function evtCancelAttendee(&$evt)
    {

        $a = $this->getAttendee($evt);
        if ($a === null) {
            $this->logger->error("evtCancelAttendee() bad attendee");
            return;
        }

        $a->parameters['PARTSTAT']->setValue('DECLINED');

        if (!isset($evt->SUMMARY)) {
            $evt->add('SUMMARY');
        } // ???
        $evt->SUMMARY->setValue($a->parameters['CN']->getValue());

        $evt->STATUS->setValue('CANCELLED');

        if (!isset($evt->TRANSP)) {
            $evt->add('TRANSP');
        }
        $evt->TRANSP->setValue("TRANSPARENT");

    }


    /**
     * Returns Array [
     *          Localized DateTime string,
     *          "dtsamp,dtstart,dtend" (string)
     *          $tz_data for new appointment can be one of:
     *                  VTIMEZONE data,
     *                  'L' = floating (default)
     *                  'UTC' for UTC/GMT
     *          $title the title might need to be reset to original when the appointment is canceled (can be empty)
     * ]
     * @param string $data
     * @return string[]
     * @noinspection PhpDocMissingThrowsInspection
     */
    function dataDeleteAppt($data)
    {
        $f = "";
        $vo = $this->getAppointment($data, 'CONFIRMED');
        if ($vo === null) {
            return ['', '', $f, ''];
        }

        /** @var \Sabre\VObject\Component\VEvent $evt */
        $evt = $vo->VEVENT;

        if (isset($evt->DTSTART) && isset($evt->DTEND)) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $dt = (new \DateTime('now', new \DateTimeZone('utc')))->format("Ymd\THis") . "Z," .
                rtrim($evt->DTSTART->getRawMimeDirValue(), 'Z') . "," .
                rtrim($evt->DTEND->getRawMimeDirValue(), 'Z');

            if (!$evt->DTSTART->isFloating()) {
                if (isset($evt->DTSTART['TZID']) && isset($vo->VTIMEZONE)) {
                    $f = $vo->VTIMEZONE->serialize();
                    if (empty($f)) {
                        $f = 'UTC';
                    } // <- ???
                } else {
                    $f = 'UTC';
                }
            }
        } else {
            $dt = "";
        }

        $title = "";
        $xad = explode(chr(31), $this->decrypt(
            $evt->{BackendUtils::XAD_PROP}->getValue(),
            $evt->UID->getValue()));

        // @see dataSetAttendee() $xad=...
        if (count($xad) > 1 && !empty($xad[1]) && $xad[1][0] === '_') {
            $title = $xad[1];
        }

        return [$this->getDateTimeString(
            $evt->DTSTART->getDateTime(),
            $evt->{self::TZI_PROP}->getValue()
        ), $dt, $f, $title];
    }

    /**
     * @param \Sabre\VObject\Component\VEvent $evt
     * @return \Sabre\VObject\Property|null
     */
    function getAttendee($evt)
    {
        $r = null;
        $ao = null;

        $ov = $evt->ORGANIZER->getValue();
        $ov = trim(substr($ov, strpos($ov, ":") + 1));

        $aa = $evt->ATTENDEE;
        $c = count($aa);
        for ($i = 0; $i < $c; $i++) {
            $a = $aa[$i];
            $v = $a->getValue();
            if (isset($a->parameters['CN']) && isset($a->parameters['PARTSTAT'])) {
                // Some external clients set SCHEDULE-STATUS to 3.7 because of the "acct" scheme
                if (isset($a->parameters['SCHEDULE-STATUS'])) {
                    unset($a->parameters['SCHEDULE-STATUS']);
                }
                // Some external clients add organizer as attendee we only use if it is the only attendee (testing), otherwise we look for the one that does NOT match the organizer
                if ($ov === trim(substr($v, strpos($v, ":") + 1))) {
                    $ao = $a;
                    continue;
                }
                $r = $a;
                break;
            }
        }
        return $r !== null ? $r : $ao;
    }

    /**
     * @param string $uid
     * @return string|null
     */
    function getApptHash($uid)
    {
        $query = $this->db->getQueryBuilder();
        $query->select(['hash'])
            ->from(self::HASH_TABLE_NAME)
            ->where($query->expr()->eq('uid', $query->createNamedParameter($uid)));
        $stmt = $query->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if (!$row) {
            return null;
        } else {
            return $row['hash'];
        }
    }

    function setApptHash(\Sabre\VObject\Component\VEvent $evt, string $userId, string $pageId, $uri = null)
    {
        if (!isset($evt->UID)) {
            $this->logger->error("can't set appt_hash, no UID");
            return;
        }
        if (!isset($evt->DTSTART)) {
            $this->logger->error("can't set appt_hash, no DTSTART");
            return;
        }

        $status = null;
        if (isset($evt->STATUS)) {
            switch ($evt->STATUS->getValue()) {
                case "TENTATIVE":
                    $status = self::PREF_STATUS_TENTATIVE;
                    break;
                case "CONFIRMED":
                    $status = self::PREF_STATUS_CONFIRMED;
                    if (rand(0, 10) > 5) {
                        // cleanup hash table once in while
                        // TODO: use "start" instead of hash after 2022-01-02
                        $cutoff_str = (new \DateTime())->modify('-42 days')->format(BackendUtils::FLOAT_TIME_FORMAT);
                        $query = $this->db->getQueryBuilder();
                        $query->delete(BackendUtils::HASH_TABLE_NAME)
                            ->where($query->expr()->lt('hash',
                                $query->createNamedParameter($cutoff_str)))
                            ->execute();
                    }
                    break;
                case "CANCELLED":
                    $status = self::PREF_STATUS_CANCELLED;
                    break;
                default;
                    $status = null;
            }
        }

        $uid = $evt->UID->getValue();

        $query = $this->db->getQueryBuilder();

        $start_ts = $evt->DTSTART->getDateTime()->getTimestamp();
        if ($this->getApptHash($uid) === null) {

            $values = [
                'uid' => $query->createNamedParameter($uid),
                'hash' => $query->createNamedParameter(
                    $this->makeApptHash($evt)),
                'user_id' => $query->createNamedParameter($userId),
                'start' => $query->createNamedParameter($start_ts),
                'status' => $query->createNamedParameter($status),
                'page_id' => $query->createNamedParameter($pageId),
            ];
            if ($uri !== null) {
                $values['uri'] = $query->createNamedParameter($pageId);
            }

            $query->insert(self::HASH_TABLE_NAME)
                ->values($values)
                ->execute();
        } else {
            $query->update(self::HASH_TABLE_NAME)
                ->set('uid', $query->createNamedParameter($uid))
                ->set('hash', $query->createNamedParameter(
                    $this->makeApptHash($evt)))
                ->set('start', $query->createNamedParameter($start_ts))
                ->set('status', $query->createNamedParameter($status))
                ->set('page_id', $query->createNamedParameter($pageId));
            if ($uri !== null) {
                $query->set('uri', $query->createNamedParameter($uri));
            }

            $query->where($query->expr()->eq('uid', $query->createNamedParameter($uid)))
                ->execute();
        }
    }

    function deleteApptHash($evt)
    {

        if (!isset($evt->UID)) {
            $this->logger->error("can't delete appt_hash, no UID");
            return;
        }

        $this->deleteApptHashByUID(
            $this->db,
            $evt->UID->getValue()
        );
    }

    function deleteApptHashByUID(IDBConnection $db, string $uid)
    {
        $query = $db->getQueryBuilder();
        $query->delete(self::HASH_TABLE_NAME)
            ->where($query->expr()->eq('uid',
                $query->createNamedParameter($uid)))
            ->execute();
    }


    function makeApptHash($evt)
    {
        // !! ORDER IS IMPORTANT - DO NOT CHANGE !! //
        $hs = "";
        if (isset($evt->DTSTART)) {
            $hs .= str_replace("T", ".", $evt->DTSTART->getRawMimeDirValue());
        } else {
            $hs .= "99999999.000000";
        }
        if (isset($evt->STATUS)) {
            $hs .= hash("crc32", $evt->STATUS->getValue(), false);
        } else {
            $hs .= "00000000";
        }
        if (isset($evt->LOCATION)) {
            $hs .= hash("crc32", $evt->LOCATION->getValue(), false);
        } else {
            $hs .= "00000000";
        }
        return $hs;
    }

    /**
     * @param string $hash
     * @param \Sabre\VObject\Component\VEvent $evt
     * @return bool
     */
    function isApptCancelled($hash, $evt)
    {
        // 1e5189eb = hash("crc32", "CANCELLED", false)
        return $evt->STATUS->getValue() === "CANCELLED" && substr($hash, 15, 8) === "1e5189eb";
    }

    /**
     * @param string $hash
     * @return float
     */
    function getHashDTStart($hash)
    {
        // TODO: this really should be the DTEND
        return (float)substr($hash, 0, 15);
    }

    /**
     * Returns null when there are no changes, array otherwise:
     *  [index 0 - true if DTSTART changed,
     *   index 1 - true if STATUS changed,
     *   index 2 - true if LOCATION changed]
     *
     * @param string $hash
     * @param \Sabre\VObject\Component\VEvent $evt
     * @return bool[]|null
     */
    function getHashChanges($hash, $evt)
    {
        $evt_hash = $this->makeApptHash($evt);
        if ($hash === $evt_hash) {
            return null;
        } // not changed

        return [
            substr($hash, 0, 15) !== substr($evt_hash, 0, 15),
            substr($hash, 15, 8) !== substr($evt_hash, 15, 8),
            substr($hash, 23, 8) !== substr($evt_hash, 23, 8)
        ];
    }

    /**
     * @param \Sabre\VObject\Component\VEvent $evt
     */
    function setSEQ($evt)
    {
        if (!isset($evt->SEQUENCE)) {
            $evt->add('SEQUENCE', 1);
        } else {
            $sv = intval($evt->SEQUENCE->getValue());
            $evt->SEQUENCE->setValue($sv + 1);
        }
        if (!isset($evt->{'LAST-MODIFIED'})) {
            $evt->add('LAST-MODIFIED');
        }
        $evt->{'LAST-MODIFIED'}->setValue(new \DateTime());
    }

    /**
     * @param string $data
     * @param string $status fail is STATUS does not match
     * @return \Sabre\VObject\Document|null
     */
    function getAppointment($data, $status)
    {
        $vo = Reader::read($data);

        if ($vo === null || !isset($vo->VEVENT)) {
            $this->logger->error("Bad Data: not an event");
            return null;
        }
        /** @var \Sabre\VObject\Component\VEvent $evt */
        $evt = $vo->VEVENT;

        if (!$evt->DTSTART->hasTime()) {
            // no all-day events
            return null;
        }

        if (!isset($evt->STATUS) || ($status !== "*" && $evt->STATUS->getValue() !== $status)) {
            $this->logger->error("Bad Status: must be " . $status);
            return null;
        }

        if (!isset($evt->CATEGORIES) || $evt->CATEGORIES->getValue() !== BackendUtils::APPT_CAT) {
            $this->logger->error("Bad Category: not an " . BackendUtils::APPT_CAT);
            return null;
        }

        if (!isset($evt->{self::TZI_PROP})) {
            $this->logger->error("Missing " . self::TZI_PROP . " property");
            return null;
        }

        if ($this->getAttendee($evt) === null) {
            $this->logger->error("Bad ATTENDEE attribute");
            return null;
        }

        return $vo;
    }

    function getDefaultSettingsData(): array
    {
        return [
            self::PAGE_ENABLED => false,
            self::PAGE_LABEL => "",

            self::ORG_NAME => "",
            self::ORG_EMAIL => "",
            self::ORG_ADDR => "",
            self::ORG_PHONE => "",

            self::ORG_CONFIRMED_RDR_URL => "",
            self::ORG_CONFIRMED_RDR_ID => false,
            self::ORG_CONFIRMED_RDR_DATA => false,

            self::CLS_MAIN_ID => '-1',
            self::CLS_DEST_ID => '-1',

            self::CLS_XTM_SRC_ID => '-1',
            self::CLS_XTM_DST_ID => '-1',
            self::CLS_XTM_PUSH_REC => true,
            self::CLS_XTM_REQ_CAT => false,
            self::CLS_XTM_AUTO_FIX => false,

            self::CLS_TMM_DST_ID => '-1',
            self::CLS_TMM_MORE_CALS => [],
            self::CLS_TMM_SUBSCRIPTIONS => [],
            self::CLS_TMM_SUBSCRIPTIONS_SYNC => '0',

            self::CLS_PREP_TIME => "0",
            self::CLS_BUFFER_BEFORE => 0,
            self::CLS_BUFFER_AFTER => 0,
            self::CLS_ON_CANCEL => 'reset',
            self::CLS_ALL_DAY_BLOCK => false,
            self::CLS_TITLE_TEMPLATE => '',

            self::CLS_PRIVATE_PAGE => false,
            self::CLS_TS_MODE => self::CLS_TS_MODE_TEMPLATE,

            self::EML_ICS => false,
            self::EML_SKIP_EVS => false,
            self::EML_AMOD => true,
            self::EML_ADEL => true,
            self::EML_MREQ => false,
            self::EML_MCONF => true,
            self::EML_MCNCL => false,
            self::EML_VLD_TXT => "",
            self::EML_CNF_TXT => "",
            self::EML_ICS_TXT => "",

            self::PSN_FORM_TITLE => "",
            self::PSN_NWEEKS => "2",
            self::PSN_EMPTY => true,
            self::PSN_FNED => false, // start at first not empty day
            self::PSN_WEEKEND => false,
            self::PSN_TIME2 => false,
            self::PSN_END_TIME => false,
            self::PSN_HIDE_TEL => false,
            self::PSN_SHOW_TZ => false,
            self::PSN_GDPR => "",
            self::PSN_GDPR_NO_CHB => false,
            self::PSN_PAGE_TITLE => "",
            self::PSN_PAGE_SUB_TITLE => "",
            self::PSN_META_NO_INDEX => true,
            self::PSN_PAGE_STYLE => "",
            self::PSN_USE_NC_THEME => false,

            self::KEY_TMPL_DATA => [[], [], [], [], [], [], []],
            self::KEY_TMPL_INFO => [
                self::TMPL_TZ_NAME => "",
                self::TMPL_TZ_DATA => ""
            ],

            self::KEY_FORM_INPUTS_HTML => "",
            self::KEY_FORM_INPUTS_JSON => [],

            self::TALK_ENABLED => false,
            self::TALK_DEL_ROOM => false,
            self::TALK_EMAIL_TXT => "",
            self::TALK_LOBBY => false,
            self::TALK_PASSWORD => false,
            // 0=Name+DT, 1=DT+Name, 2=Name Only
            self::TALK_NAME_FORMAT => 0,
            self::TALK_FORM_ENABLED => false,
            self::TALK_FORM_LABEL => "",
            self::TALK_FORM_PLACEHOLDER => "",
            self::TALK_FORM_REAL_TXT => "",
            self::TALK_FORM_VIRTUAL_TXT => "",
            self::TALK_FORM_TYPE_CHANGE_TXT => "",
            self::TALK_FORM_DEF_LABEL => 'Meeting Type',
            self::TALK_FORM_DEF_PLACEHOLDER => 'Select meeting type',
            self::TALK_FORM_DEF_REAL => 'In-person meeting',
            self::TALK_FORM_DEF_VIRTUAL => 'Online (audio/video)',
            self::TALK_INTEGRATION_DISABLED => false,

            self::KEY_REMINDERS => [
                self::REMINDER_DATA => [
                    [
                        self::REMINDER_DATA_TIME => "0",
                        self::REMINDER_DATA_ACTIONS => true
                    ],
                    [
                        self::REMINDER_DATA_TIME => "0",
                        self::REMINDER_DATA_ACTIONS => true
                    ],
                    [
                        self::REMINDER_DATA_TIME => "0",
                        self::REMINDER_DATA_ACTIONS => true
                    ],
                ],
                self::REMINDER_SEND_ON_FRIDAY => false,
                self::REMINDER_MORE_TEXT => ""
            ],

            self::DEBUGGING_LOG_REM_BLOCKER => false,
        ];
    }

    function getDefaultSettingsDataForDirPage(): array
    {
        return [
            self::DIR_ITEMS => [],
            self::PSN_PAGE_TITLE => "",
            self::PSN_PAGE_STYLE => "",
            self::PSN_USE_NC_THEME => false,
            self::CLS_PRIVATE_PAGE => false,
        ];
    }

    /**
     * @throws Exception
     */
    function loadSettingsForUserAndPage(string $userId, string $pageId): bool
    {
        $qb = $this->db->getQueryBuilder();
        $r = $qb->select(self::KEY_TOKEN, self::KEY_DATA, self::KEY_REMINDERS)
            ->from(self::PREF_TABLE_V2_NAME)
            ->where($qb->expr()->eq(self::KEY_USER_ID,
                $qb->createNamedParameter($userId)))
            ->andWhere($qb->expr()->eq(self::KEY_PAGE_ID,
                $qb->createNamedParameter($pageId)))
            ->executeQuery();
        $row = $r->fetch();
        $r->closeCursor();

        $isDir = $this->isDir($pageId);

        if ($row === false) {
            if ($isDir) {
                // Dir rows do not exist if there is no dirData,
                // but we load required defaults
                $this->settings = $this->getDefaultSettingsDataForDirPage();
                $this->settings[self::KEY_TOKEN] = '';
                return true;
            } else {
                return false;
            }
        }
        return $this->parseSettings($row, $isDir);
    }

    private function parseSettings(array $row, bool $isDir = false): bool
    {
        $userSettings = json_decode($row[self::KEY_DATA], true);
        if ($userSettings === null) {
            return false;
        }

        $this->settings = $isDir
            ? $this->getDefaultSettingsDataForDirPage()
            : $this->getDefaultSettingsData();
        foreach ($userSettings as $k => $v) {
            if (isset($this->settings[$k]) && $this->settings[$k] !== $v) {
                $this->settings[$k] = $v;
            }
        }

        if (isset($row[self::KEY_TOKEN])) {
            $this->settings[self::KEY_TOKEN] = $row[self::KEY_TOKEN];
        }

        if (!$isDir) {

            if ($this->settings[self::TALK_ENABLED]) {
                $this->settings[self::TALK_FORM_DEF_LABEL] = $this->l10n->t('Meeting Type');
                $this->settings[self::TALK_FORM_DEF_PLACEHOLDER] = $this->l10n->t('Select meeting type');
                $this->settings[self::TALK_FORM_DEF_REAL] = $this->l10n->t('In-person meeting');
                $this->settings[self::TALK_FORM_DEF_VIRTUAL] = $this->l10n->t('Online (audio/video)');
            }

            if (isset($row[self::KEY_REMINDERS])) {
                $reminders = json_decode($row[self::KEY_REMINDERS], true);
                if ($reminders !== null) {
                    // replace default
                    $this->settings[self::KEY_REMINDERS] = $reminders;
                }
            }
        }

        return true;
    }


    /**
     * @throws Exception
     */
    function getUserPages($userId, $includeDirPages = false): array
    {
        $qb = $this->db->getQueryBuilder();
        $r = $qb->select(self::KEY_PAGE_ID, self::KEY_DATA)
            ->from(self::PREF_TABLE_V2_NAME)
            ->where($qb->expr()->eq(self::KEY_USER_ID,
                $qb->createNamedParameter($userId)))
            ->executeQuery();
        $pages = [];

        while ($row = $r->fetch()) {
            $pageId = $row[self::KEY_PAGE_ID];
            if ($this->isDir($pageId) === false) {
                $data = json_decode($row[self::KEY_DATA], true);
                if ($data === null) {
                    $this->logger->error($row[self::KEY_DATA]);
                    $this->logger->warning("invalid data for pageId: " . $pageId);
                    continue;
                }

                $isEnabled = ($data[self::PAGE_ENABLED] ?? false);
                if ($isEnabled) {
                    // a quick check if the page can actually be ebaled
                    $orgEmail = ($data[self::ORG_EMAIL] ?? '');
                    if (empty($orgEmail) || !filter_var($orgEmail, FILTER_VALIDATE_EMAIL)) {
                        $this->settings = $data;
                        $this->setUserSettingsV2($userId, $pageId, self::PAGE_ENABLED, false);
                        $isEnabled = false;
                    }
                }

                $pages[] = [
                    'id' => $pageId,
                    'type' => 'page',
                    self::PAGE_ENABLED => $isEnabled,
                    self::PAGES_LABEL => ($data[self::PAGE_LABEL] ?? '')
                ];
            } elseif ($includeDirPages) {
                $pages[] = [
                    'id' => $pageId,
                    'type' => 'dir',
                    'hasData' => strlen($row[self::KEY_DATA]) > 4,
                ];
            }
        }
        $r->closeCursor();
        return $pages;
    }

    function isDir(string $pageId): bool
    {
        return $pageId[0] === 'd';
    }

    function getTemplateData($pageId, $userId)
    {
        // TODO: inline/simplify
        return $this->settings[self::KEY_TMPL_DATA];
    }


    function clearSettingsCache()
    {
        $this->settings = null;
    }

    function getUserSettings(): array
    {

        if ($this->settings === null) {
            // this should never happen
            throw new \Exception("internal error: settings not loaded");
        }

        return $this->settings;
    }

    // This is a temp work-around for multiple "template mode" pages, use this instead getUserSettings(BackendUtils::KEY_TMPL_INFO, $userId) until data is normalized.
    function getTemplateInfo(string $userId, string $pageId): array
    {
        // TODO: inline/simplify
        return $this->settings[self::KEY_TMPL_INFO];
    }

    /**
     * @param string $userId
     * @param string $key
     * @param string|null $value
     */
    function setDBValue($userId, $key, $value)
    {
        try {
            $qb = $this->db->getQueryBuilder();
            $r = $qb->update(self::PREF_TABLE_NAME)
                ->set($key, $qb->createNamedParameter($value))
                ->where($qb->expr()->eq('user_id', $qb->createNamedParameter($userId)))
                ->execute();
            if ($r === 0) {
                $qb = $this->db->getQueryBuilder();
                $qb->insert(self::PREF_TABLE_NAME)
                    ->setValue('user_id', $qb->createNamedParameter($userId))
                    ->setValue($key, $qb->createNamedParameter($value))
                    ->execute();
            }
            // set cached
            $this->settings[$key] = $value;
            return true;
        } catch (\Exception $e) {
            $this->logger->error($e);
            return false;
        }
    }

    function dbUpsert2(string $userId, string $pageId, array $columns)
    {
        try {
            $tableName = self::PREF_TABLE_V2_NAME;

            $qb = $this->db->getQueryBuilder();
            $qb->update($tableName)
                ->where($qb->expr()->eq(
                    self::KEY_USER_ID, $qb->createNamedParameter($userId)))
                ->andWhere($qb->expr()->eq(
                    self::KEY_PAGE_ID, $qb->createNamedParameter($pageId)));
            // self::KEY_DATA, self::KEY_REMINDERS is set
            $r = $this->setQbValues($qb, $columns, false)
                ->executeStatement();
            if ($r === 0) {
                $qb = $this->db->getQueryBuilder();
                $qb->insert($tableName)
                    ->setValue(self::KEY_USER_ID, $qb->createNamedParameter($userId))
                    ->setValue(self::KEY_PAGE_ID, $qb->createNamedParameter($pageId));
                // self::KEY_TOKEN, self::KEY_DATA, self::KEY_REMINDERS
                $this->setQbValues($qb, $columns, true)
                    ->executeStatement();
            }
            return true;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [
                'app' => Application::APP_ID,
                'exception' => $e
            ]);
            return false;
        }
    }

    private function setQbValues(IQueryBuilder $qb, array $values, bool $isInsert): IQueryBuilder
    {
        foreach ([self::KEY_TOKEN, self::KEY_DATA, self::KEY_REMINDERS] as $column) {
            if (key_exists($column, $values)) {
                if ($isInsert) {
                    $qb->setValue($column, $qb->createNamedParameter($values[$column]));
                } elseif ($column !== self::KEY_TOKEN) {
                    $qb->set($column, $qb->createNamedParameter($values[$column]));
                }
            }
        }
        return $qb;
    }


    function deletePage(string $userId, string $pageId): int
    {
        $qb = $this->db->getQueryBuilder();
        $count = 0;
        try {
            $count = $qb->delete(self::PREF_TABLE_V2_NAME)
                ->where($qb->expr()->eq('user_id',
                    $qb->createNamedParameter($userId)))
                ->andWhere($qb->expr()->eq('page_id',
                    $qb->createNamedParameter($pageId)))
                ->execute();
        } catch (Exception $e) {
            $this->logger->error("deletePage error: " . $e->getMessage());
        }
        return $count;
    }

    function createPage($userId, $pageId, $data): int
    {
        // make sure the token is not used
        do {
            $token = $this->makePageToken();
            $qb = $this->db->getQueryBuilder();
            $r = $qb->select('id')
                ->from(self::PREF_TABLE_V2_NAME)
                ->where($qb->expr()->eq(self::KEY_TOKEN,
                    $qb->createNamedParameter($token)))
                ->executeQuery();

            $id = $r->fetch();
        } while ($id !== false);

        $qb = $this->db->getQueryBuilder();
        $count = $qb->insert(self::PREF_TABLE_V2_NAME)
            ->setValue(self::KEY_TOKEN, $qb->createNamedParameter($token))
            ->setValue(self::KEY_USER_ID, $qb->createNamedParameter($userId))
            ->setValue(self::KEY_PAGE_ID, $qb->createNamedParameter($pageId))
            ->setValue(self::KEY_DATA, $qb->createNamedParameter($data))
            ->executeStatement();
        // count should be 1, because we are inserting one row/page
        return $count;
    }

    private function makePageToken(int $count = 16): string
    {
        $charPool = 'ABCDEGMNQYZabcrltvwqyz012345V789';
        $out = '';
        $max = strlen($charPool) - 1;
        for ($i = 0; $i < $count; $i++) {
            $out .= $charPool[random_int(0, $max)];
        }
        return $out;
    }

    function setUserSettingsV2(string $userId, string $pageId, string $key, $value): array
    {

        if ($key === self::KEY_REMINDERS) {
            return $this->setUserReminders($userId, $pageId, $value);
        }

        $settings = $this->settings;
        $settings[$key] = $value;
        $settingsStr = json_encode($this->filterDefaultSettings($settings));
        if ($settingsStr === false) {
            $this->logger->warning("setUserSettingsV2: json_encode failed");
            return [500, ''];
        }

        $qb = $this->db->getQueryBuilder();
        try {
            $r = $qb->update(self::PREF_TABLE_V2_NAME)
                ->set(self::KEY_DATA, $qb->createNamedParameter($settingsStr))
                ->where($qb->expr()->eq(self::KEY_USER_ID,
                    $qb->createNamedParameter($userId)))
                ->andWhere($qb->expr()->eq(self::KEY_PAGE_ID,
                    $qb->createNamedParameter($pageId)))
                ->executeStatement();
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), [
                'app' => Application::APP_ID,
                'exception' => $e
            ]);
            return [500, ''];
        }
        if ($r !== 1) {
            $this->logger->warning("setUserSettingsV2: wrong row count " . $r);
            return [500, ''];
        }

        $this->settings = $settings;
        return [200, ''];
    }

    private function setUserReminders(string $userId, string $pageId, array $value): array
    {
        // if all reminders are set to 0 we need to set DB value to null
        $zeroCount = 0;
        foreach ($value[self::REMINDER_DATA] as $rd) {
            if ($rd[self::REMINDER_DATA_TIME] === "0") {
                $zeroCount++;
            }
        }

        $dbValue = $zeroCount === count($value[self::REMINDER_DATA]) ? null : json_encode($value);
        if ($dbValue === false) {
            $this->logger->error('json_encode failed in ' . __FUNCTION__);
            return [500, ''];
        }

        if (true === $this->dbUpsert2($userId, $pageId, [self::KEY_REMINDERS => $dbValue])) {
            return [200, ''];
        } else {
            return [500, ''];
        }
    }

    function filterDefaultSettings(array $data, $defaults = null): array
    {
        if ($defaults === null) {
            $defaults = $this->getDefaultSettingsData();
            // we don't want 'reminders' here
            unset($defaults[self::KEY_REMINDERS]);
        }

        $filteredData = [];
        foreach ($data as $k => $v) {
            if (isset($defaults[$k]) && $defaults[$k] !== $v) {
                $filteredData[$k] = $v;
            }
        }
        return $filteredData;
    }


    /**
     * For simple mode:
     *  Main = CLS_MAIN_ID
     *  Other = CLS_DEST_ID
     *
     * For external mode:
     *  Main = XTM_DST_ID (destination calendar)
     *  Other = XTM_SRC_ID (source calendar)
     *
     * @param string $userId
     * @param string $pageId
     * @param IBackendConnector|null $bc checks backend if provided
     * @param string|null $otherCal get the ID of the other calendar "-1"=not found
     * @return string calendar Id or "-1" = no main cal
     */
    function getMainCalId($userId, $pageId, $bc, &$otherCal = null)
    {

        $settings = $this->getUserSettings();

        // What mode are we in ??
        $ts_mode = $settings[self::CLS_TS_MODE];
        if ($ts_mode === self::CLS_TS_MODE_TEMPLATE) {
            $dst = $settings[self::CLS_TMM_DST_ID];
            return ($bc !== null && $bc->getCalendarById($dst, $userId) === null) ? '-1' : $dst;
        } else {
            if ($ts_mode === self::CLS_TS_MODE_EXTERNAL) {
                $dst = $settings[self::CLS_XTM_DST_ID];
                $src = $settings[self::CLS_XTM_SRC_ID];
                // External mode - main calendar is destination calendar
                if ($src === "-1" || $dst === "-1" || $src === $dst) {
                    if (isset($otherCal)) {
                        $otherCal = '-1';
                    }
                    return "-1";
                } else {
                    if (isset($otherCal)) {
                        $otherCal = ($bc !== null && $bc->getCalendarById($src, $userId) === null) ? '-1' : $src;
                    }
                    return ($bc !== null && $bc->getCalendarById($dst, $userId) === null) ? "-1" : $dst;
                }
            } else {
                // Manual $ts_mode==="0"
                if (isset($otherCal)) {
                    $dst = $settings[self::CLS_DEST_ID];
                    $otherCal = ($bc !== null && $bc->getCalendarById($dst, $userId) === null) ? '-1' : $dst;
                }
                $src = $settings[self::CLS_MAIN_ID];
                return ($bc !== null && $bc->getCalendarById($src, $userId) === null) ? '-1' : $src;
            }
        }
    }

    /**
     * @param string $userId
     * @param string $pageId
     * @param string $appName
     * @param string $tz_data_str Can be VTIMEZONE data, 'UTC'
     * @param string $cr_date 20200414T073008Z must be UTC (ends with Z),
     * @param string $title title is used when the appointment is being reset
     * @return string[] ['1_before_uid'=>'string...','2_before_dts'=>'string...','3_before_dte'=>'string...','4_last'=>'string...'] or ['err'=>'Error text...']
     */
    function makeAppointmentParts($userId, $pageId, $appName, $tz_data_str, $cr_date, $title = "")
    {

        $l10n = $this->l10n;
        $iUser = \OC::$server->getUserManager()->get($userId);
        if ($iUser === null) {
            return ['err' => 'Bad user Id.'];
        }
        $rn = "\r\n";
        $cr_date_rn = $cr_date . "\r\n";

        $tz_id = "";
        $tz_Z = "";
        $tz_data = "";
        if ($tz_data_str !== "UTC" && !empty($tz_data_str)) {
            $tzo = Reader::read("BEGIN:VCALENDAR\r\nPRODID:-//IDN nextcloud.com//Appointments App//EN\r\nCALSCALE:GREGORIAN\r\nVERSION:2.0\r\n" . $tz_data_str . "\r\nEND:VCALENDAR");
            if (isset($tzo->VTIMEZONE) && isset($tzo->VTIMEZONE->TZID)) {
                $tz_id = ';TZID=' . $tzo->VTIMEZONE->TZID->getValue();
                $tz_data = trim($tzo->VTIMEZONE->serialize()) . "\r\n";
            }
        } else {
            $tz_Z = "Z";
        }

        $settings = $this->getUserSettings();
        $org_name = $settings[BackendUtils::ORG_NAME];
        $addr = $settings[BackendUtils::ORG_ADDR];
        $email = $settings[self::ORG_EMAIL];

        $name = trim($iUser->getDisplayName());
        if (empty($name)) {
            $name = $org_name;
        }

        if (empty($email)) {
            $email = $iUser->getEMailAddress();
        }
        if (empty($email)) {
            return ['err' => $l10n->t("Your email address is required for this operation.")];
        }
        if (!empty($addr)) {
//        ESCAPED-CHAR = ("\\" / "\;" / "\," / "\N" / "\n")
//        \\ encodes \ \N or \n encodes newline \; encodes ; \, encodes ,
            $addr = str_replace(array("\\", ";", ",", "\r\n", "\r", "\n"), array('\\\\', '\;', '\,', ' \n', ' \n', ' \n'), $addr);
        }

        if (empty($name)) {
            return ['err' => $l10n->t("Can't find your name. Check User/Organization settings.")];
        }

        if (empty($title)) {
            $summary = $this->l10n->t("Available");
        } else {
            $summary = $title;
        }

        return [
            '1_before_uid' => "BEGIN:VCALENDAR\r\n" .
                "PRODID:-//IDN nextcloud.com//Appointments App | srgdev.com//EN\r\n" .
                "CALSCALE:GREGORIAN\r\n" .
                "VERSION:2.0\r\n" .
                "BEGIN:VEVENT\r\n" .
                "SUMMARY:" . $summary . $rn .
                "STATUS:TENTATIVE\r\n" .
                "TRANSP:TRANSPARENT\r\n" .
                "LAST-MODIFIED:" . $cr_date_rn .
                "DTSTAMP:" . $cr_date_rn .
                "SEQUENCE:1\r\n" .
                "CATEGORIES:" . BackendUtils::APPT_CAT . $rn .
                "CREATED:" . $cr_date_rn . "UID:", // UID goes here
            '2_before_dts' => $rn . "DTSTART" . $tz_id . ":", // DTSTART goes here
            '3_before_dte' => $tz_Z . $rn . "DTEND" . $tz_id . ":", // DTEND goes here
            '4_last' => $tz_Z . $rn
                . $this->chunk_split_unicode("ORGANIZER;CN=" . $name . ":mailto:" . $email, 75, "\r\n ") . $rn
                . (!empty($addr) ? ($this->chunk_split_unicode("LOCATION:" . $addr, 75, "\r\n ") . $rn) : '')
                . "END:VEVENT\r\n" . $tz_data . "END:VCALENDAR\r\n"
        ];
    }

    private function chunk_split_unicode($str, $l = 76, $e = "\r\n")
    {
        $tmp = array_chunk(
            preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $l);
        $str = "";
        foreach ($tmp as $t) {
            $str .= join("", $t) . $e;
        }
        return trim($str);
    }

    /**
     * Try to get calendar timezone if it is not available fall back to getUserTimezone
     *
     * @param string $userId
     * @param \OCP\IConfig $config
     * @param array|null $cal
     * @return \DateTimeZone
     *
     * @see getUserTimezone
     */
    function getCalendarTimezone(string $userId, \OCP\IConfig $config, array $cal = null): \DateTimeZone
    {

        // TODO: Double check if the following is the Calendar App order (#1 and #2 might be reversed):
        // 1. $config->getUserValue($userId, 'calendar', 'timezone');
        // 2. $cal['timezone']
        // 3. $config->getUserValue($userId, 'core', 'timezone')
        // 4. \OC::$server->getDateTimeZone()->getTimeZone();

        $err = "";
        $tz = null;

        if ($cal === null) {
            $err = "Calendar for user " . $userId . " is null";
        } elseif (empty($cal['timezone'])) {
            $err = "Calendar with ID " . $cal['id'] . " for user " . $userId . " missing 'timezone' prop";
        } else {
            $token = 'TZID:';
            $tokenPos = strpos($cal['timezone'], $token);
            if ($tokenPos === false) {
                $err = "Bad timezone data, calendarId: " . $cal['id'] . ", userId: " . $userId;
            } else {
                try {
                    $tz_start = $tokenPos + strlen($token);
                    $tz_name = trim(substr(
                        $cal['timezone'],
                        $tz_start,
                        strpos($cal['timezone'], "\n", $tz_start) - $tz_start));
                    $tz = new \DateTimeZone($tz_name);
                } catch (\Exception $e) {
                    $this->logger->error("getCalendarTimezone error: " . $e->getMessage());
                    $tz = new \DateTimeZone('utc'); // fallback to utc
                }
            }
        }
        if ($tz === null) {
            $this->logger->notice("getCalendarTimezone fallback to getUserTimezone: " . $err);
            return $this->getUserTimezone($userId, $config);
        }
        return $tz;
    }

    /**
     * @param $userId
     * @param \OCP\IConfig $config
     * @return \DateTimeZone
     */
    function getUserTimezone($userId, $config)
    {
        $tz_name = $config->getUserValue($userId, 'calendar', 'timezone');
        if (empty($tz_name) || strpos($tz_name, 'auto') !== false) {
            // Try Nextcloud default timezone
            $tz_name = $config->getUserValue($userId, 'core', 'timezone');
            if (empty($tz_name) || strpos($tz_name, 'auto') !== false) {
                return \OC::$server->getDateTimeZone()->getTimeZone();
            }
        }
        try {
            $tz = new \DateTimeZone($tz_name);
        } catch (\Exception $e) {
            $this->logger->error("getUserTimezone error: " . $e->getMessage());
            $tz = new \DateTimeZone('utc'); // fallback to utc
        }

        return $tz;
    }

    /**
     * @param \DateTimeImmutable $date
     * @param string $tzi Timezone info [UF][+-]\d{4} Ex: U+0300 @see dataSetAttendee() or [UF](valid timezone name) Ex: UAmerica/New_York
     * @param int $short_dt
     *      0 = long format
     *      1 = short format (for email subject)
     * @return string
     * @noinspection PhpDocMissingThrowsInspection
     */
    function getDateTimeString($date, $tzi, $short_dt = 0)
    {

        $l10N = $this->l10n;
        if ($tzi[0] === "F") {
            $d = $date->format('Ymd\THis');
            if ($short_dt === 0) {
                $date_time =
                    $l10N->l('date', $d, ['width' => 'full']) . ', ' .
                    $l10N->l('time', $d, ['width' => 'short']);
            } else {
                if ($short_dt === 1) {
                    $date_time = $l10N->l('datetime', $d, ['width' => 'short']);
                } else {
                    $date_time = '';
                }
            }
        } else {
            try {
                $d = new \DateTime('now', new \DateTimeZone(substr($tzi, 1)));
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                /** @noinspection PhpUnhandledExceptionInspection */
                $d = new \DateTime('now', $date->getTimezone());
            }
            $d->setTimestamp($date->getTimestamp());

            if ($short_dt === 0) {
                $date_time = $l10N->l('date', $d, ['width' => 'full']) . ', ' .
                    str_replace(':00 ', ' ',
                        $l10N->l('time', $d, ['width' => 'full']));
            } else {
                if ($short_dt === 1) {
                    $date_time = $l10N->l('datetime', $d, ['width' => 'short']);
                } else {
                    $date_time = '';
                }
            }
        }

        return $date_time;
    }

    /**
     * @param string $data
     * @param string $key
     * @param string $iv special case
     * @return string
     */
    function encrypt(string $data, string $key, $iv = ''): string
    {
        if ($iv === '') {
            $iv = $_iv = openssl_random_pseudo_bytes(
                openssl_cipher_iv_length(self::CIPHER));
        } else {
            $_iv = '';
        }
        $ciphertext_raw = openssl_encrypt(
            $data,
            self::CIPHER,
            $key,
            OPENSSL_RAW_DATA,
            $iv);

        return $_iv !== ''
            ? base64_encode($_iv . $ciphertext_raw)
            : $_iv . $ciphertext_raw;
    }

    /**
     * @param string $data
     * @param string $key
     * @param string $iv
     * @return string
     */
    function decrypt(string $data, string $key, $iv = ''): string
    {
        $s1 = $iv === '' ? base64_decode($data) : $data;
        if ($s1 === false || empty($key)) {
            return '';
        }

        $s1 = $iv . $s1;

        $ivlen = openssl_cipher_iv_length(self::CIPHER);
        $t = openssl_decrypt(
            substr($s1, $ivlen),
            self::CIPHER,
            $key,
            OPENSSL_RAW_DATA,
            substr($s1, 0, $ivlen));
        return $t === false ? '' : $t;
    }


    /**
     * @param string $token
     * @param bool $embed
     * @return string
     */
    function pubPrx($token, $embed)
    {
        return $embed ? 'embed/' . $token . '/' : 'pub/' . $token . '/';
    }


    function getPublicWebBase()
    {
        // we need to detect if the server is configured to use '.../index.php/...'
        $defaultUrl = $this->urlGenerator->linkToDefaultPageUrl();
        return substr($defaultUrl, 0, strpos($defaultUrl, '/apps/') + 6) . Application::APP_ID;
    }

    function verifyToken(string $token): array
    {
        if (empty($token) || strlen($token) > 92) {
            return [null, null];
        }

        $qb = $this->db->getQueryBuilder();
        $r = $qb->select(self::KEY_TOKEN, self::KEY_USER_ID, self::KEY_PAGE_ID, self::KEY_DATA)
            ->from(self::PREF_TABLE_V2_NAME)
            ->where($qb->expr()->eq(self::KEY_TOKEN, $qb->createNamedParameter($token)))
            ->executeQuery();
        $row = $r->fetch();
        $r->closeCursor();

        if ($row === false) {
            return [null, null];
        }

        $userId = $row[self::KEY_USER_ID];
        $pageId = $row[self::KEY_PAGE_ID];
        if (empty($userId) || empty($pageId)) {
            return [null, null];
        }

        if ($this->parseSettings($row) === false) {
            return [null, null];
        }

        return [$userId, $pageId];
    }


    /**
     * @param string $userId
     * @param string $pageId (optional)
     * @return string
     * @throws \ErrorException
     */
    function getToken($userId, $pageId = "p0"): string|null
    {
        if (empty($userId)) {
            return null;
        }

        $qb = $this->db->getQueryBuilder();
        $r = $qb->select(self::KEY_TOKEN)
            ->from(self::PREF_TABLE_V2_NAME)
            ->where($qb->expr()->eq(self::KEY_USER_ID, $qb->createNamedParameter($userId)))
            ->andWhere($qb->expr()->eq(self::KEY_PAGE_ID, $qb->createNamedParameter($pageId)))
            ->executeQuery();
        $row = $r->fetch();
        $r->closeCursor();

        if ($row === false || !isset($row[self::KEY_TOKEN])) {
            return null;
        }

        return $row[self::KEY_TOKEN];

    }

    function transformCalInfo($c, $skipReadOnly = true)
    {

        if (isset($c['{http://nextcloud.com/ns}deleted-at'])) {
            // skip "trash bin" calendars (calendars are placed into the "trash bin" and the deleted after 30 days)
            return null;
        }

        $isReadOnlyCal = isset($c['{http://owncloud.org/ns}read-only'])
            && $c['{http://owncloud.org/ns}read-only'] === true;

        if ($skipReadOnly && $isReadOnlyCal) {
            // Do not use read only calendars
            return null;
        }

        $a = [];
        $a['id'] = (string)$c["id"];
        $a['displayName'] = $c['{DAV:}displayname'] ?? "Calendar";
        $a['color'] = $c['{http://apple.com/ns/ical/}calendar-color'] ?? "#000000";
        $a['uri'] = $c['uri'];
        $a['timezone'] = $c['{urn:ietf:params:xml:ns:caldav}calendar-timezone'] ?? '';
        $a['isReadOnly'] = $isReadOnlyCal ? '1' : '0';
        return $a;
    }

    /**
     * @param array $currentIds Ex: ['1','2',...]
     * @param array $real Ex: [['id'=>'1','x'=>'y'],['id'=>'2','x'=>'y'],...]
     * @return array
     */
    function filterCalsAndSubs(array $currentIds, array $real): array
    {
        // convert to array with ids as keys for fast look up
        $ids = [];
        for ($i = 0, $l = count($real); $i < $l; $i++) {
            $ids[$real[$i]['id']] = true;
        }

        $curLen = count($currentIds);
        $realIds = [];
        for ($i = 0; $i < $curLen; $i++) {
            $id = $currentIds[$i];
            if (isset($ids[$id])) {
                $realIds[] = $id;
            }
        }
        return $realIds;
    }

    function removeSubscriptionSync($subscriptionId)
    {
        $this->logger->info("removeSubscriptionSync, subscriptionId: " . $subscriptionId);
        $qb = $this->db->getQueryBuilder();
        try {
            $qb->delete(self::SYNC_TABLE_NAME)
                ->where($qb->expr()->eq('id',
                    $qb->createNamedParameter($subscriptionId)))
                ->execute();
        } catch (Exception $e) {
            $this->logger->error("removeSubscriptionSync error: " . $e->getMessage());
        }
    }

    private function makeEvtTitle(string $userId, string $attendeeName, string $pageId, string $av): string
    {
        $settings = $this->getUserSettings();
        if (isset($settings[self::CLS_TITLE_TEMPLATE]) && !empty($settings[self::CLS_TITLE_TEMPLATE])) {

            $tmpl = $settings[self::CLS_TITLE_TEMPLATE];

            $pageTag = $this->l10n->t('Public Page') . ' ' . $pageId;
            if (!empty($settings[self::PAGE_LABEL])) {
                $pageTag = $settings[self::PAGE_LABEL];
            }

            $tkn = strtoupper(substr(str_replace(' ', '', $attendeeName), 0, 3)) .
                strtoupper(substr(str_replace(['+', '/', '='], '', base64_encode(sha1($attendeeName . $av . $userId, true))), 0, 8));

            // %N = Attendee Name
            // %O = Organization Name
            // %P = Page Tag
            // %T = Mask Token
            return str_replace(["%N", "%O", "%P", "%T"],
                [$attendeeName, $settings[self::ORG_NAME], $pageTag, $tkn], $tmpl);
        } else {
            return $attendeeName;
        }
    }

    public function getInlineStyle(string $userId, array $pps, IConfig $config): string
    {

        // TODO: rename pps to settings

        $autoStyle = "";

        if ($pps[BackendUtils::PSN_USE_NC_THEME]
            && $config->getAppValue('theming', 'disable-user-theming', 'no') !== 'yes') {

            $appointmentsBackgroundImage = "var(--image-background-default)";
            $appointmentsBackgroundColor = "transparent";

            // use system-wide default background color if provided
            $backgroundMime = $config->getAppValue('theming', 'backgroundMime');
            if ($backgroundMime === 'backgroundColor') {
                $appointmentsBackgroundImage = "none";
                $appointmentsBackgroundColor = $config->getAppValue('theming', 'color');
            }

            try {
                /** @var \OCP\App\IAppManager $appManager */
                $appManager = \OC::$server->get(\OCP\App\IAppManager::class);
                if ($appManager->isEnabledForUser('theming', $userId)) {

                    $themingBackground = $config->getUserValue($userId, 'theming', 'background', 'default');
                    if ($themingBackground === 'default') {
                        // nc26
                        $themingBackground = $config->getUserValue($userId, 'theming', 'background_image', 'default');
                    }
                    if (isset(\OCA\Theming\Service\BackgroundService::SHIPPED_BACKGROUNDS[$themingBackground])) {
                        /** @var IURLGenerator $urlGenerator */
                        $urlGenerator = \OC::$server->get(IURLGenerator::class);
                        $appointmentsBackgroundImage = "url('" . $urlGenerator->linkTo('theming', "/img/background/$themingBackground") . "');";
                    } elseif ($themingBackground[0] === "#" || substr($themingBackground, 0, 3) === "rgb") {
                        $appointmentsBackgroundImage = "none";
                        $appointmentsBackgroundColor = $themingBackground;
                    } else {
                        // nc26
                        $themingBackground = $config->getUserValue($userId, 'theming', 'background_color');
                        if (!empty($themingBackground)) {
                            $appointmentsBackgroundImage = "none";
                            $appointmentsBackgroundColor = $themingBackground;
                        }
                    }
                }
            } catch (\Throwable $e) {
                $this->logger->warning($e->getMessage());
            }

            /** @noinspection CssUnresolvedCustomProperty */
            $autoStyle = '<style>body{background-image:' . $appointmentsBackgroundImage . ';background-color:' . $appointmentsBackgroundColor . '}#header{background:none}#srgdev-ncfp_frm,.srgdev-appt-info-cont,.appt-dir-lnk{background-color:var(--color-main-background-blur);-webkit-backdrop-filter:var(--filter-background-blur);backdrop-filter:var(--filter-background-blur);border-radius:10px;padding:2em}#srgdev-dpu_main-cont{border-radius:8px}@media only screen and (max-width:390px){#srgdev-ncfp_frm{padding:1em;margin-left:0;margin-right:0}.srgdev-ncfp-wrap{font-size:105%}}</style>';
        } else {
            /** @noinspection CssUnresolvedCustomProperty */
            $autoStyle = '<style>:root{--image-main-background:var(--image-background, var(--image-background-plain, var(--image-background-default)))}</style>';
        }
        return $autoStyle . $pps[BackendUtils::PSN_PAGE_STYLE];
    }

}

