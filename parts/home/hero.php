<?php
/**
 * Homepage, top: "Is Prithul ___?", headline, buttons and the schematic probe.
 * The schematic is drawn like a KiCad sheet; assets/js/schematic.js makes it clickable.
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

$isp_hero   = isp_c( 'hero' );
$isp_probe  = isp_c( 'probe' );
$isp_on     = isp_available();
$isp_status = $isp_on ? isp_c( 'site.statusOn' ) : isp_c( 'site.statusOff' );
$isp_q0     = $isp_hero['questions'][0] ?? null;
$isp_a      = $isp_q0 ? isp_kicker_answer( $isp_q0 ) : array( '#projects', '', '' );
?>
<section class="hero" aria-labelledby="hero-title">
  <div class="wrap hero__grid">
    <div class="hero__copy">
      <p class="kicker" data-kicker>
        <button type="button" class="kicker__q" data-kicker-next title="Next question">
          <span class="kicker__fixed"><?php echo esc_html( $isp_hero['questionStart'] ); ?></span> <span data-kicker-q><?php echo esc_html( $isp_q0 ? $isp_q0['q'] : '' ); ?></span><span class="kicker__caret" aria-hidden="true"></span><span class="kicker__next" aria-hidden="true">↻</span>
        </button>
        <a class="kicker__a" data-kicker-a href="<?php echo esc_attr( $isp_a[0] ); ?>"<?php echo $isp_a[1]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built with esc_attr. ?>><?php echo esc_html( $isp_a[2] ); ?></a>
      </p>
      <h1 class="hero__title" id="hero-title"><?php echo esc_html( $isp_hero['title'] ); ?></h1>
      <p class="hero__sub"><?php echo esc_html( $isp_hero['intro'] ); ?></p>
      <div class="hero__cta">
        <a class="btn btn--primary" href="#contact" data-open-brief><?php echo esc_html( $isp_hero['button'] ); ?></a>
        <a class="btn btn--ghost" href="#projects"><?php echo esc_html( $isp_hero['secondButton'] ); ?></a>
      </div>
      <p class="status"><span class="led" aria-hidden="true"></span><span data-status-text><?php echo esc_html( $isp_status ); ?></span></p>
    </div>

    <div class="bench" data-nosnippet>
      <div class="sch-frame">
        <svg class="sch" data-sch viewBox="0 0 640 396" role="group" aria-labelledby="sch-title">
          <title id="sch-title">Schematic of what I do: ISP header J1 into microcontroller U1 (firmware), I2C to U2 (electronics), step and direction to driver U3 and motor M1 (custom systems), and status LED D1.</title>
          <defs>
            <pattern id="sgrid" width="10" height="10" patternUnits="userSpaceOnUse" x="18" y="18"><circle class="s-grid" cx="0.7" cy="0.7" r="0.7"/></pattern>
          </defs>
          <rect class="s-gridbg" x="18" y="18" width="604" height="360" fill="url(#sgrid)"/>

          <!-- drawing sheet: frame, zones, title block -->
          <g class="s-sheet" aria-hidden="true">
            <rect class="s-frame" x="6" y="6" width="628" height="384"/>
            <rect class="s-frame" x="18" y="18" width="604" height="360"/>
            <path class="s-frame" d="M169 6V18M320 6V18M471 6V18M169 378V390M320 378V390M471 378V390M6 138H18M6 258H18M622 138H634M622 258H634"/>
            <g text-anchor="middle">
              <text aria-hidden="true" class="s-zone" x="93.5" y="15">1</text><text aria-hidden="true" class="s-zone" x="244.5" y="15">2</text><text aria-hidden="true" class="s-zone" x="395.5" y="15">3</text><text aria-hidden="true" class="s-zone" x="546.5" y="15">4</text>
              <text aria-hidden="true" class="s-zone" x="93.5" y="387">1</text><text aria-hidden="true" class="s-zone" x="244.5" y="387">2</text><text aria-hidden="true" class="s-zone" x="395.5" y="387">3</text><text aria-hidden="true" class="s-zone" x="546.5" y="387">4</text>
              <text aria-hidden="true" class="s-zone" x="12" y="81">A</text><text aria-hidden="true" class="s-zone" x="12" y="201">B</text><text aria-hidden="true" class="s-zone" x="12" y="321">C</text>
              <text aria-hidden="true" class="s-zone" x="628" y="81">A</text><text aria-hidden="true" class="s-zone" x="628" y="201">B</text><text aria-hidden="true" class="s-zone" x="628" y="321">C</text>
            </g>
            <rect class="s-frame" x="418" y="318" width="204" height="60"/>
            <path class="s-frame" d="M418 338H622M418 358H622M486 358V378M554 358V378"/>
            <text aria-hidden="true" class="s-tb" x="424" y="332">Title: <tspan class="s-tb-v">isprithul.me</tspan></text>
            <text aria-hidden="true" class="s-tb" x="424" y="352">File: home.kicad_sch</text>
            <text aria-hidden="true" class="s-tb" x="424" y="372">Rev: C</text><text aria-hidden="true" class="s-tb" x="492" y="372">Size: A4</text><text aria-hidden="true" class="s-tb" x="560" y="372">Id: 1/1</text>
          </g>

          <!-- power symbols (+3V3 everywhere: the ISP header senses the target voltage) -->
          <path class="s-pwr" d="M56 126V116M52 121L56 116L60 121"/><text aria-hidden="true" class="s-pwr-t" x="56" y="110" text-anchor="middle">+3V3</text>
          <path class="s-pwr" d="M56 250V255M50 255H62L56 261Z"/>
          <path class="s-pwr" d="M268 108V98M264 103L268 98L272 103"/><text aria-hidden="true" class="s-pwr-t" x="268" y="92" text-anchor="middle">+3V3</text>
          <path class="s-pwr" d="M296 272V277M290 277H302L296 283Z"/>
          <path class="s-pwr" d="M400 44V36M396 41L400 36L404 41"/><text aria-hidden="true" class="s-pwr-t" x="400" y="30" text-anchor="middle">+3V3</text>
          <path class="s-pwr" d="M240 338V343M234 343H246L240 349Z"/>

          <!-- nets -->
          <g class="net is-on" data-net="isp">
            <path class="s-wire" d="M116 156H188M116 176H188M116 196H188M116 216H188"/>
            <path class="s-hit" d="M116 156H188M116 176H188M116 196H188M116 216H188"/>
          </g>
          <text aria-hidden="true" class="s-label" x="128" y="151">ISP</text>
          <g class="net net--alt" data-net="i2c">
            <path class="s-wire" d="M348 140H392V80H440M348 160H408V100H440M392 80V74M408 100V74M392 52V44M408 52V44M392 44H408"/>
            <circle class="s-jn" cx="392" cy="80" r="2.6"/><circle class="s-jn" cx="408" cy="100" r="2.6"/><circle class="s-jn" cx="400" cy="44" r="2.6"/>
            <path class="s-hit" d="M348 140H392V80H440M348 160H408V100H440"/>
          </g>
          <text aria-hidden="true" class="s-label" x="354" y="135">I2C</text>
          <g class="net" data-net="motion">
            <path class="s-wire" d="M348 220H420M348 240H420M544 221H572.8M544 231H566.6M544 241H566.6M544 251H572.8"/>
            <path class="s-hit" d="M348 220H420M348 240H420M544 221H572.8M544 231H566.6M544 241H566.6M544 251H572.8"/>
          </g>
          <g class="net" data-net="led">
            <path class="s-wire" d="M240 272V280M240 302V312M240 326V338"/>
          </g>

          <!-- passives -->
          <rect class="s-res" x="388" y="52" width="8" height="22"/><rect class="s-res" x="404" y="52" width="8" height="22"/>
          <text aria-hidden="true" class="s-ref s-pass" x="382" y="61" text-anchor="end">R1</text><text aria-hidden="true" class="s-val s-pass" x="382" y="71" text-anchor="end">4k7</text>
          <text aria-hidden="true" class="s-ref s-pass" x="417" y="61">R2</text><text aria-hidden="true" class="s-val s-pass" x="417" y="71">4k7</text>
          <rect class="s-res" x="236" y="280" width="8" height="22"/>
          <text aria-hidden="true" class="s-ref s-pass" x="250" y="289">R3</text><text aria-hidden="true" class="s-val s-pass" x="250" y="299">1k</text>

          <!-- J1: AVR ISP header -->
          <g class="part" data-part="j1" tabindex="0" role="button" aria-label="J1, ISP header: where your project plugs in">
            <rect class="s-body" x="40" y="140" width="64" height="96"/>
            <path class="s-pin" d="M104 156H116M104 176H116M104 196H116M104 216H116M56 140V126M56 236V250"/>
            <text aria-hidden="true" class="s-pinname" x="98" y="159" text-anchor="end">MISO</text><text aria-hidden="true" class="s-pinname" x="98" y="179" text-anchor="end">SCK</text>
            <text aria-hidden="true" class="s-pinname" x="98" y="199" text-anchor="end">MOSI</text><text aria-hidden="true" class="s-pinname" x="98" y="219" text-anchor="end"><tspan class="s-ob">RST</tspan></text>
            <text aria-hidden="true" class="s-pinname" x="56" y="151" text-anchor="middle">VCC</text><text aria-hidden="true" class="s-pinname" x="56" y="231" text-anchor="middle">GND</text>
            <text aria-hidden="true" class="s-pinnum" x="107" y="153">1</text><text aria-hidden="true" class="s-pinnum" x="107" y="173">3</text><text aria-hidden="true" class="s-pinnum" x="107" y="193">4</text><text aria-hidden="true" class="s-pinnum" x="107" y="213">5</text>
            <text aria-hidden="true" class="s-pinnum" x="60" y="136">2</text><text aria-hidden="true" class="s-pinnum" x="60" y="246">6</text>
            <text aria-hidden="true" class="s-ref" x="74" y="132">J1</text><text aria-hidden="true" class="s-val" x="40" y="278">AVR-ISP-6</text>
          </g>
          <text aria-hidden="true" class="s-note" x="34" y="302"><?php echo esc_html( $isp_probe['j1Note'] ); ?></text>

          <g class="tp is-on" data-net="isp" tabindex="0" role="button" aria-label="Probe TP1 on the ISP bus">
            <circle class="tp-hit" cx="152" cy="196" r="12"/><circle class="tp-dot" cx="152" cy="196" r="3.6"/>
            <text aria-hidden="true" class="tp-l" x="152" y="189" text-anchor="middle">TP1</text>
          </g>

          <!-- U1: microcontroller = FIRMWARE -->
          <g class="part is-on" data-part="u1" tabindex="0" role="button" aria-label="U1, firmware">
            <rect class="s-body" x="200" y="120" width="136" height="140"/>
            <path class="s-pin" d="M188 156H200M188 176H200M188 196H200M188 216H200M336 140H348M336 160H348M336 220H348M336 240H348M268 120V108M240 260V272M296 260V272"/>
            <text aria-hidden="true" class="s-pinname" x="206" y="159">MISO</text><text aria-hidden="true" class="s-pinname" x="206" y="179">SCK</text><text aria-hidden="true" class="s-pinname" x="206" y="199">MOSI</text><text aria-hidden="true" class="s-pinname" x="206" y="219"><tspan class="s-ob">RESET</tspan></text>
            <text aria-hidden="true" class="s-pinname" x="330" y="143" text-anchor="end">SDA</text><text aria-hidden="true" class="s-pinname" x="330" y="163" text-anchor="end">SCL</text>
            <text aria-hidden="true" class="s-pinname" x="330" y="223" text-anchor="end">STEP</text><text aria-hidden="true" class="s-pinname" x="330" y="243" text-anchor="end">DIR</text>
            <text aria-hidden="true" class="s-pinname" x="268" y="131" text-anchor="middle">VCC</text>
            <text aria-hidden="true" class="s-pinname" x="240" y="255" text-anchor="middle">PD6</text><text aria-hidden="true" class="s-pinname" x="296" y="255" text-anchor="middle">GND</text>
            <text aria-hidden="true" class="s-val-big" x="268" y="188" text-anchor="middle">FIRMWARE</text>
            <text aria-hidden="true" class="s-val" x="268" y="201" text-anchor="middle">ATmega328P</text>
            <text aria-hidden="true" class="s-ref" x="200" y="114">U1</text>
          </g>

          <g class="tp" data-net="i2c" tabindex="0" role="button" aria-label="Probe TP2 on the I2C bus">
            <circle class="tp-hit" cx="426" cy="80" r="12"/><circle class="tp-dot" cx="426" cy="80" r="3.6"/>
            <text aria-hidden="true" class="tp-l" x="426" y="93" text-anchor="middle">TP2</text>
          </g>

          <!-- U2: sensor/peripheral = EMBEDDED -->
          <g class="part" data-part="u2" tabindex="0" role="button" aria-label="U2, electronics">
            <rect class="s-body" x="452" y="56" width="114" height="72"/>
            <path class="s-pin" d="M440 80H452M440 100H452"/>
            <text aria-hidden="true" class="s-pinname" x="458" y="83">SDA</text><text aria-hidden="true" class="s-pinname" x="458" y="103">SCL</text>
            <text aria-hidden="true" class="s-val" x="522" y="95" text-anchor="middle">SENSORS · I/O</text>
            <text aria-hidden="true" class="s-val-big" x="452" y="144">ELECTRONICS</text>
            <text aria-hidden="true" class="s-ref" x="452" y="50">U2</text>
          </g>

          <g class="tp" data-net="motion" tabindex="0" role="button" aria-label="Probe TP3 on the step and direction lines">
            <circle class="tp-hit" cx="384" cy="220" r="12"/><circle class="tp-dot" cx="384" cy="220" r="3.6"/>
            <text aria-hidden="true" class="tp-l" x="384" y="213" text-anchor="middle">TP3</text>
          </g>

          <!-- U3 + M1: stepper driver and motor = CUSTOM SYSTEMS -->
          <g class="part" data-part="u3" tabindex="0" role="button" aria-label="U3 and M1, custom systems">
            <rect class="s-body" x="432" y="196" width="100" height="80"/>
            <path class="s-pin" d="M420 220H432M420 240H432M532 221H544M532 231H544M532 241H544M532 251H544"/>
            <text aria-hidden="true" class="s-pinname" x="438" y="223">STEP</text><text aria-hidden="true" class="s-pinname" x="438" y="243">DIR</text>
            <text aria-hidden="true" class="s-pinname" x="526" y="224" text-anchor="end">A1</text><text aria-hidden="true" class="s-pinname" x="526" y="234" text-anchor="end">A2</text>
            <text aria-hidden="true" class="s-pinname" x="526" y="244" text-anchor="end">B1</text><text aria-hidden="true" class="s-pinname" x="526" y="254" text-anchor="end">B2</text>
            <text aria-hidden="true" class="s-val" x="482" y="210" text-anchor="middle">TMC2209</text>
            <text aria-hidden="true" class="s-ref" x="432" y="190">U3</text>
            <text aria-hidden="true" class="s-val-big" x="432" y="292">CUSTOM SYSTEMS</text>
            <circle class="s-body" cx="586" cy="236" r="20"/>
            <text aria-hidden="true" class="s-ref" x="586" y="240" text-anchor="middle">M</text>
            <text aria-hidden="true" class="s-ref" x="586" y="209" text-anchor="middle">M1</text><text aria-hidden="true" class="s-val" x="586" y="272" text-anchor="middle">NEMA 17</text>
          </g>

          <!-- D1: status LED (follows the availability switch) -->
          <g class="part d1<?php echo $isp_on ? ' is-lit' : ''; ?>" data-part="d1" tabindex="0" role="button" aria-label="D1, status LED: availability">
            <rect x="206" y="306" width="68" height="28" fill="transparent"/>
            <path class="led-sym" d="M230 312H250L240 326Z"/><path class="s-pin" d="M230 326H250"/>
            <g class="rays"><path class="ray" d="M252 316L260 308M254 323L262 315"/><path class="ray-h" d="M262 306L260.6 311.6L256.4 307.4Z"/><path class="ray-h" d="M264 313L262.6 318.6L258.4 314.4Z"/></g>
            <text aria-hidden="true" class="s-ref" x="224" y="318" text-anchor="end">D1</text><text aria-hidden="true" class="s-val" x="224" y="329" text-anchor="end">STATUS</text>
          </g>
          <text aria-hidden="true" class="s-note" x="270" y="342" data-d1-note><?php echo esc_html( $isp_on ? $isp_probe['d1NoteOn'] : $isp_probe['d1NoteOff'] ); ?></text>

          <!-- crosshair -->
          <line class="xh" data-xh-x x1="320" y1="18" x2="320" y2="378"/>
          <line class="xh" data-xh-y x1="18" y1="200" x2="622" y2="200"/>
        </svg>
        <span class="sch-xy" data-sch-xy hidden></span>
      </div>
      <script>if(window.matchMedia&&matchMedia("(max-width: 640px)").matches){document.querySelector("[data-sch]").setAttribute("viewBox","30 26 582 326")}</script>
      <div class="tp-chips" data-tp-chips role="group" aria-label="Test points"><?php get_template_part( 'parts/home/probe-chips' ); ?></div>
      <div class="instruments">
        <div class="probe" data-probe aria-live="polite"><?php get_template_part( 'parts/home/probe-card' ); ?></div>
        <div class="scope">
          <div class="scope__top"><span data-scope-ch>CH1 SCK · CH2 MOSI</span><span class="scope__trig">TRIG'D ↑CH1</span></div>
          <canvas data-scope width="400" height="96" role="img" aria-label="Oscilloscope capture of the probed net"></canvas>
          <div class="scope__dec" data-scope-dec><i>SPI</i> · <b>AC 53 00 00</b> · Programming Enable</div>
        </div>
      </div>
    </div>
  </div>
</section>
