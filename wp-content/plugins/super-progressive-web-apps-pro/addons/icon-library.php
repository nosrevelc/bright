<?php
	return array(
				/*'pwa-lilfp-vb' => array(
					'name' 		 => 'pwa-lilfp-vb',//verticle bar
					'class_name' => 'pwa-lilfp-vb',
					'content_html'=> '<div class="pwa-lilfp-vb"></div>',
					'css'		 => '.pwa-lilfp-vb,.pwa-lilfp-vb:after,.pwa-lilfp-vb:before{background:#fff;-webkit-animation:load1 1s infinite ease-in-out;animation:load1 1s infinite ease-in-out;width:1em;height:4em}.pwa-lilfp-vb{color:#fff;text-indent:-9999em;margin:0px auto;position:relative;font-size:7px;-webkit-transform:translateZ(0);-ms-transform:translateZ(0);transform:translateZ(0);-webkit-animation-delay:-.16s;animation-delay:-.16s}.pwa-lilfp-vb:after,.pwa-lilfp-vb:before{position:absolute;top:0;content:\'\'}.pwa-lilfp-vb:before{left:-1.5em;-webkit-animation-delay:-.32s;animation-delay:-.32s}.pwa-lilfp-vb:after{left:1.5em}@-webkit-keyframes load1{0%,100%,80%{box-shadow:0 0;height:4em}40%{box-shadow:0 -2em;height:5em}}@keyframes load1{0%,100%,80%{box-shadow:0 0;height:4em}40%{box-shadow:0 -2em;height:5em}}'
					),*/
				'superpwa-ball-clip-rotate' => array(
					'name' 		 => 'superpwa-ball-clip-rotate',//verticle bar
					'class_name' => 'superpwa-ball-clip-rotate',
					'content_html'=> '<div class="superpwa-ball-clip-rotate"><div></div></div>',
					'css'		 => '.superpwa-ball-clip-rotate > div {
								  background-color: #fff;
								  width: 15px;
								  height: 15px;
								  border-radius: 100%;
								  margin: 2px;
								  -webkit-animation-fill-mode: both;
								          animation-fill-mode: both;
								  border: 2px solid {{selected_color}};
								  border-bottom-color: transparent;
								  height: 26px;
								  width: 26px;
								  background: transparent !important;
								  display: inline-block;
								  -webkit-animation: rotate 0.75s 0s linear infinite;
								          animation: rotate 0.75s 0s linear infinite; }

								@keyframes rotate {
								  0% {
								    -webkit-transform: rotate(0deg) scale(1);
								            transform: rotate(0deg) scale(1); }
								  50% {
								    -webkit-transform: rotate(180deg) scale(0.6);
								            transform: rotate(180deg) scale(0.6); }
								  100% {
								    -webkit-transform: rotate(360deg) scale(1);
								            transform: rotate(360deg) scale(1); } }

								@keyframes scale {
								  30% {
								    -webkit-transform: scale(0.3);
								            transform: scale(0.3); }
								  100% {
								    -webkit-transform: scale(1);
								            transform: scale(1); } }'
					),
				'superpwa-ball-pulse' => array(
					'name' 		 => 'superpwa-ball-pulse',//verticle bar
					'class_name' => 'superpwa-ball-pulse',
					'content_html'=> '<div class="superpwa-ball-pulse"><div></div><div></div><div></div></div>',
					'css'		 => '@-webkit-keyframes scale {
									  0% {
									    -webkit-transform: scale(1);
									            transform: scale(1);
									    opacity: 1; }
									  45% {
									    -webkit-transform: scale(0.1);
									            transform: scale(0.1);
									    opacity: 0.7; }
									  80% {
									    -webkit-transform: scale(1);
									            transform: scale(1);
									    opacity: 1; } }
									@keyframes scale {
									  0% {
									    -webkit-transform: scale(1);
									            transform: scale(1);
									    opacity: 1; }
									  45% {
									    -webkit-transform: scale(0.1);
									            transform: scale(0.1);
									    opacity: 0.7; }
									  80% {
									    -webkit-transform: scale(1);
									            transform: scale(1);
									    opacity: 1; } }

									.superpwa-ball-pulse > div:nth-child(1) {
									  -webkit-animation: scale 0.75s -0.24s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08);
									          animation: scale 0.75s -0.24s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08); }

									.superpwa-ball-pulse > div:nth-child(2) {
									  -webkit-animation: scale 0.75s -0.12s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08);
									          animation: scale 0.75s -0.12s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08); }

									.superpwa-ball-pulse > div:nth-child(3) {
									  -webkit-animation: scale 0.75s 0s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08);
									          animation: scale 0.75s 0s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08); }

									.superpwa-ball-pulse > div {
									  background-color: {{selected_color}};
									  width: 15px;
									  height: 15px;
									  border-radius: 100%;
									  margin: 2px;
									  -webkit-animation-fill-mode: both;
									          animation-fill-mode: both;
									  display: inline-block; }'
					),

				'superpwa-ball-grid-pulse' => array(
					'name' 		 => 'superpwa-ball-grid-pulse',//verticle bar
					'class_name' => 'superpwa-ball-grid-pulse',
					'content_html'=> '<div class="superpwa-ball-grid-pulse"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
					'css'		 => '@-webkit-keyframes superpwa-ball-grid-pulse {
										  0% {
										    -webkit-transform: scale(1);
										            transform: scale(1); }
										  50% {
										    -webkit-transform: scale(0.5);
										            transform: scale(0.5);
										    opacity: 0.7; }
										  100% {
										    -webkit-transform: scale(1);
										            transform: scale(1);
										    opacity: 1; } }

										@keyframes superpwa-ball-grid-pulse {
										  0% {
										    -webkit-transform: scale(1);
										            transform: scale(1); }
										  50% {
										    -webkit-transform: scale(0.5);
										            transform: scale(0.5);
										    opacity: 0.7; }
										  100% {
										    -webkit-transform: scale(1);
										            transform: scale(1);
										    opacity: 1; } }

										.superpwa-ball-grid-pulse {
										  width: 57px; }
										  .superpwa-ball-grid-pulse > div:nth-child(1) {
										    -webkit-animation-delay: 0.22s;
										            animation-delay: 0.22s;
										    -webkit-animation-duration: 0.9s;
										            animation-duration: 0.9s; }
										  .superpwa-ball-grid-pulse > div:nth-child(2) {
										    -webkit-animation-delay: 0.64s;
										            animation-delay: 0.64s;
										    -webkit-animation-duration: 1s;
										            animation-duration: 1s; }
										  .superpwa-ball-grid-pulse > div:nth-child(3) {
										    -webkit-animation-delay: -0.15s;
										            animation-delay: -0.15s;
										    -webkit-animation-duration: 0.63s;
										            animation-duration: 0.63s; }
										  .superpwa-ball-grid-pulse > div:nth-child(4) {
										    -webkit-animation-delay: -0.03s;
										            animation-delay: -0.03s;
										    -webkit-animation-duration: 1.24s;
										            animation-duration: 1.24s; }
										  .superpwa-ball-grid-pulse > div:nth-child(5) {
										    -webkit-animation-delay: 0.08s;
										            animation-delay: 0.08s;
										    -webkit-animation-duration: 1.37s;
										            animation-duration: 1.37s; }
										  .superpwa-ball-grid-pulse > div:nth-child(6) {
										    -webkit-animation-delay: 0.43s;
										            animation-delay: 0.43s;
										    -webkit-animation-duration: 1.55s;
										            animation-duration: 1.55s; }
										  .superpwa-ball-grid-pulse > div:nth-child(7) {
										    -webkit-animation-delay: 0.05s;
										            animation-delay: 0.05s;
										    -webkit-animation-duration: 0.7s;
										            animation-duration: 0.7s; }
										  .superpwa-ball-grid-pulse > div:nth-child(8) {
										    -webkit-animation-delay: 0.05s;
										            animation-delay: 0.05s;
										    -webkit-animation-duration: 0.97s;
										            animation-duration: 0.97s; }
										  .superpwa-ball-grid-pulse > div:nth-child(9) {
										    -webkit-animation-delay: 0.3s;
										            animation-delay: 0.3s;
										    -webkit-animation-duration: 0.63s;
										            animation-duration: 0.63s; }
										  .superpwa-ball-grid-pulse > div {
										    background-color: {{selected_color}};
										    width: 15px;
										    height: 15px;
										    border-radius: 100%;
										    margin: 2px;
										    -webkit-animation-fill-mode: both;
										            animation-fill-mode: both;
										    display: inline-block;
										    float: left;
										    -webkit-animation-name: superpwa-ball-grid-pulse;
										            animation-name: superpwa-ball-grid-pulse;
										    -webkit-animation-iteration-count: infinite;
										            animation-iteration-count: infinite;
										    -webkit-animation-delay: 0;
										            animation-delay: 0; }'
					),
				'superpwa-ball-clip-rotate-pulse' => array(
					'name' 		 => 'superpwa-ball-clip-rotate-pulse',//verticle bar
					'class_name' => 'superpwa-ball-clip-rotate-pulse',
					'content_html'=> '<div class="superpwa-ball-clip-rotate-pulse"><div></div><div></div></div>',
					'css'		 => '.superpwa-ball-clip-rotate-pulse {
									  position: relative;
									  -webkit-transform: translateY(-15px);
									          transform: translateY(-15px); }
									  .superpwa-ball-clip-rotate-pulse > div {
									    -webkit-animation-fill-mode: both;
									            animation-fill-mode: both;
									    position: absolute;
									    top: 0px;
									    left: 0px;
									    border-radius: 100%; }
									    .superpwa-ball-clip-rotate-pulse > div:first-child {
									      background: {{selected_color}};
									      height: 16px;
									      width: 16px;
									      top: 7px;
									      left: -7px;
									      -webkit-animation: scale 1s 0s cubic-bezier(0.09, 0.57, 0.49, 0.9) infinite;
									              animation: scale 1s 0s cubic-bezier(0.09, 0.57, 0.49, 0.9) infinite; }
									    .superpwa-ball-clip-rotate-pulse > div:last-child {
									      position: absolute;
									      border: 2px solid #fff;
									      width: 30px;
									      height: 30px;
									      left: -16px;
									      top: -2px;
									      background: transparent;
									      border: 2px solid;
									      border-color: {{selected_color}} transparent {{selected_color}} transparent;
									      -webkit-animation: rotate 1s 0s cubic-bezier(0.09, 0.57, 0.49, 0.9) infinite;
									              animation: rotate 1s 0s cubic-bezier(0.09, 0.57, 0.49, 0.9) infinite;
									      -webkit-animation-duration: 1s;
									              animation-duration: 1s; }

									@keyframes rotate {
									  0% {
									    -webkit-transform: rotate(0deg) scale(1);
									            transform: rotate(0deg) scale(1); }
									  50% {
									    -webkit-transform: rotate(180deg) scale(0.6);
									            transform: rotate(180deg) scale(0.6); }
									  100% {
									    -webkit-transform: rotate(360deg) scale(1);
									            transform: rotate(360deg) scale(1); } }'
					),
				'superpwa-square-spin' => array(
					'name' 		 => 'superpwa-square-spin',//verticle bar
					'class_name' => 'superpwa-square-spin',
					'content_html'=> '<div class="superpwa-square-spin"><div></div></div>',
					'css'		 => '@-webkit-keyframes superpwa-square-spin {
									  25% {
									    -webkit-transform: perspective(100px) rotateX(180deg) rotateY(0);
									            transform: perspective(100px) rotateX(180deg) rotateY(0); }
									  50% {
									    -webkit-transform: perspective(100px) rotateX(180deg) rotateY(180deg);
									            transform: perspective(100px) rotateX(180deg) rotateY(180deg); }
									  75% {
									    -webkit-transform: perspective(100px) rotateX(0) rotateY(180deg);
									            transform: perspective(100px) rotateX(0) rotateY(180deg); }
									  100% {
									    -webkit-transform: perspective(100px) rotateX(0) rotateY(0);
									            transform: perspective(100px) rotateX(0) rotateY(0); } }

									@keyframes superpwa-square-spin {
									  25% {
									    -webkit-transform: perspective(100px) rotateX(180deg) rotateY(0);
									            transform: perspective(100px) rotateX(180deg) rotateY(0); }
									  50% {
									    -webkit-transform: perspective(100px) rotateX(180deg) rotateY(180deg);
									            transform: perspective(100px) rotateX(180deg) rotateY(180deg); }
									  75% {
									    -webkit-transform: perspective(100px) rotateX(0) rotateY(180deg);
									            transform: perspective(100px) rotateX(0) rotateY(180deg); }
									  100% {
									    -webkit-transform: perspective(100px) rotateX(0) rotateY(0);
									            transform: perspective(100px) rotateX(0) rotateY(0); } }

									.superpwa-square-spin > div {
									  -webkit-animation-fill-mode: both;
									          animation-fill-mode: both;
									  width: 50px;
									  height: 50px;
									  background: {{selected_color}};
									  -webkit-animation: superpwa-square-spin 3s 0s cubic-bezier(0.09, 0.57, 0.49, 0.9) infinite;
									          animation: superpwa-square-spin 3s 0s cubic-bezier(0.09, 0.57, 0.49, 0.9) infinite; }'
					),
				'superpwa-ball-clip-rotate-multiple' => array(
					'name' 		 => 'superpwa-ball-clip-rotate-multiple',//verticle bar
					'class_name' => 'superpwa-ball-clip-rotate-multiple',
					'content_html'=> '<div class="superpwa-ball-clip-rotate-multiple"><div></div><div></div></div>',
					'css'		 => '.superpwa-ball-clip-rotate-multiple {
									  position: relative; }
									  .superpwa-ball-clip-rotate-multiple > div {
									    -webkit-animation-fill-mode: both;
									            animation-fill-mode: both;
									    position: absolute;
									    left: -20px;
									    top: -20px;
									    border: 2px solid {{selected_color}};
									    border-bottom-color: transparent;
									    border-top-color: transparent;
									    border-radius: 100%;
									    height: 35px;
									    width: 35px;
									    -webkit-animation: rotate 1s 0s ease-in-out infinite;
									            animation: rotate 1s 0s ease-in-out infinite; }
									    .superpwa-ball-clip-rotate-multiple > div:last-child {
									      display: inline-block;
									      top: -10px;
									      left: -10px;
									      width: 15px;
									      height: 15px;
									      -webkit-animation-duration: 0.5s;
									              animation-duration: 0.5s;
									      border-color: {{selected_color}} transparent {{selected_color}} transparent;
									      -webkit-animation-direction: reverse;
									              animation-direction: reverse; }'
					),
				'superpwa-ball-pulse-rise' => array(
					'name' 		 => 'superpwa-ball-pulse-rise',//verticle bar
					'class_name' => 'superpwa-ball-pulse-rise',
					'content_html'=> '<div class="superpwa-ball-pulse-rise"><div></div><div></div><div></div><div></div><div></div></div>',
					'css'		 => '@-webkit-keyframes superpwa-ball-pulse-rise-even {
										  0% {
										    -webkit-transform: scale(1.1);
										            transform: scale(1.1); }
										  25% {
										    -webkit-transform: translateY(-30px);
										            transform: translateY(-30px); }
										  50% {
										    -webkit-transform: scale(0.4);
										            transform: scale(0.4); }
										  75% {
										    -webkit-transform: translateY(30px);
										            transform: translateY(30px); }
										  100% {
										    -webkit-transform: translateY(0);
										            transform: translateY(0);
										    -webkit-transform: scale(1);
										            transform: scale(1); } }

										@keyframes superpwa-ball-pulse-rise-even {
										  0% {
										    -webkit-transform: scale(1.1);
										            transform: scale(1.1); }
										  25% {
										    -webkit-transform: translateY(-30px);
										            transform: translateY(-30px); }
										  50% {
										    -webkit-transform: scale(0.4);
										            transform: scale(0.4); }
										  75% {
										    -webkit-transform: translateY(30px);
										            transform: translateY(30px); }
										  100% {
										    -webkit-transform: translateY(0);
										            transform: translateY(0);
										    -webkit-transform: scale(1);
										            transform: scale(1); } }

										@-webkit-keyframes superpwa-ball-pulse-rise-odd {
										  0% {
										    -webkit-transform: scale(0.4);
										            transform: scale(0.4); }
										  25% {
										    -webkit-transform: translateY(30px);
										            transform: translateY(30px); }
										  50% {
										    -webkit-transform: scale(1.1);
										            transform: scale(1.1); }
										  75% {
										    -webkit-transform: translateY(-30px);
										            transform: translateY(-30px); }
										  100% {
										    -webkit-transform: translateY(0);
										            transform: translateY(0);
										    -webkit-transform: scale(0.75);
										            transform: scale(0.75); } }

										@keyframes superpwa-ball-pulse-rise-odd {
										  0% {
										    -webkit-transform: scale(0.4);
										            transform: scale(0.4); }
										  25% {
										    -webkit-transform: translateY(30px);
										            transform: translateY(30px); }
										  50% {
										    -webkit-transform: scale(1.1);
										            transform: scale(1.1); }
										  75% {
										    -webkit-transform: translateY(-30px);
										            transform: translateY(-30px); }
										  100% {
										    -webkit-transform: translateY(0);
										            transform: translateY(0);
										    -webkit-transform: scale(0.75);
										            transform: scale(0.75); } }

										.superpwa-ball-pulse-rise > div {
										  background-color: {{selected_color}};
										  width: 15px;
										  height: 15px;
										  border-radius: 100%;
										  margin: 2px;
										  -webkit-animation-fill-mode: both;
										          animation-fill-mode: both;
										  display: inline-block;
										  -webkit-animation-duration: 1s;
										          animation-duration: 1s;
										  -webkit-animation-timing-function: cubic-bezier(0.15, 0.46, 0.9, 0.6);
										          animation-timing-function: cubic-bezier(0.15, 0.46, 0.9, 0.6);
										  -webkit-animation-iteration-count: infinite;
										          animation-iteration-count: infinite;
										  -webkit-animation-delay: 0;
										          animation-delay: 0; }
										  .superpwa-ball-pulse-rise > div:nth-child(2n) {
										    -webkit-animation-name: superpwa-ball-pulse-rise-even;
										            animation-name: superpwa-ball-pulse-rise-even; }
										  .superpwa-ball-pulse-rise > div:nth-child(2n-1) {
										    -webkit-animation-name: superpwa-ball-pulse-rise-odd;
										            animation-name: superpwa-ball-pulse-rise-odd; }'
					),
				'superpwa-ball-rotate' => array(
					'name' 		 => 'superpwa-ball-rotate',//verticle bar
					'class_name' => 'superpwa-ball-rotate',
					'content_html'=> '<div class="superpwa-ball-rotate"><div></div></div>',
					'css'		 => '.superpwa-ball-rotate {
									  position: relative; }
									  .superpwa-ball-rotate > div {
									    background-color: {{selected_color}};
									    width: 15px;
									    height: 15px;
									    border-radius: 100%;
									    margin: 2px;
									    -webkit-animation-fill-mode: both;
									            animation-fill-mode: both;
									    position: relative; }
									    .superpwa-ball-rotate > div:first-child {
									      -webkit-animation: rotate 1s 0s cubic-bezier(0.7, -0.13, 0.22, 0.86) infinite;
									              animation: rotate 1s 0s cubic-bezier(0.7, -0.13, 0.22, 0.86) infinite; }
									    .superpwa-ball-rotate > div:before, .superpwa-ball-rotate > div:after {
									      background-color: {{selected_color}};
									      width: 15px;
									      height: 15px;
									      border-radius: 100%;
									      margin: 2px;
									      content: "";
									      position: absolute;
									      opacity: 0.8; }
									    .superpwa-ball-rotate > div:before {
									      top: 0px;
									      left: -28px; }
									    .superpwa-ball-rotate > div:after {
									      top: 0px;
									      left: 25px; }
'
					),

				'superpwa-cube-transition' => array(
					'name' 		 => 'superpwa-cube-transition',//verticle bar
					'class_name' => 'superpwa-cube-transition',
					'content_html'=> '<div class="superpwa-cube-transition"><div></div><div></div></div>',
					'css'		 => '@-webkit-keyframes superpwa-cube-transition {
										  25% {
										    -webkit-transform: translateX(50px) scale(0.5) rotate(-90deg);
										            transform: translateX(50px) scale(0.5) rotate(-90deg); }
										  50% {
										    -webkit-transform: translate(50px, 50px) rotate(-180deg);
										            transform: translate(50px, 50px) rotate(-180deg); }
										  75% {
										    -webkit-transform: translateY(50px) scale(0.5) rotate(-270deg);
										            transform: translateY(50px) scale(0.5) rotate(-270deg); }
										  100% {
										    -webkit-transform: rotate(-360deg);
										            transform: rotate(-360deg); } }

										@keyframes superpwa-cube-transition {
										  25% {
										    -webkit-transform: translateX(50px) scale(0.5) rotate(-90deg);
										            transform: translateX(50px) scale(0.5) rotate(-90deg); }
										  50% {
										    -webkit-transform: translate(50px, 50px) rotate(-180deg);
										            transform: translate(50px, 50px) rotate(-180deg); }
										  75% {
										    -webkit-transform: translateY(50px) scale(0.5) rotate(-270deg);
										            transform: translateY(50px) scale(0.5) rotate(-270deg); }
										  100% {
										    -webkit-transform: rotate(-360deg);
										            transform: rotate(-360deg); } }

										.superpwa-cube-transition {
										  position: relative;
										  -webkit-transform: translate(-25px, -25px);
										          transform: translate(-25px, -25px); }
										  .superpwa-cube-transition > div {
										    -webkit-animation-fill-mode: both;
										            animation-fill-mode: both;
										    width: 10px;
										    height: 10px;
										    position: absolute;
										    top: -5px;
										    left: -5px;
										    background-color: {{selected_color}};
										    -webkit-animation: superpwa-cube-transition 1.6s 0s infinite ease-in-out;
										            animation: superpwa-cube-transition 1.6s 0s infinite ease-in-out; }
										    .superpwa-cube-transition > div:last-child {
										      -webkit-animation-delay: -0.8s;
										              animation-delay: -0.8s; }'
					),
				'superpwa-ball-zig-zag' => array(
					'name' 		 => 'superpwa-ball-zig-zag',//verticle bar
					'class_name' => 'superpwa-ball-zig-zag',
					'content_html'=> '<div class="superpwa-ball-zig-zag"><div></div><div></div></div>',
					'css'		 => '@-webkit-keyframes ball-zig {
									  33% {
									    -webkit-transform: translate(-15px, -30px);
									            transform: translate(-15px, -30px); }
									  66% {
									    -webkit-transform: translate(15px, -30px);
									            transform: translate(15px, -30px); }
									  100% {
									    -webkit-transform: translate(0, 0);
									            transform: translate(0, 0); } }

									@keyframes ball-zig {
									  33% {
									    -webkit-transform: translate(-15px, -30px);
									            transform: translate(-15px, -30px); }
									  66% {
									    -webkit-transform: translate(15px, -30px);
									            transform: translate(15px, -30px); }
									  100% {
									    -webkit-transform: translate(0, 0);
									            transform: translate(0, 0); } }

									@-webkit-keyframes ball-zag {
									  33% {
									    -webkit-transform: translate(15px, 30px);
									            transform: translate(15px, 30px); }
									  66% {
									    -webkit-transform: translate(-15px, 30px);
									            transform: translate(-15px, 30px); }
									  100% {
									    -webkit-transform: translate(0, 0);
									            transform: translate(0, 0); } }

									@keyframes ball-zag {
									  33% {
									    -webkit-transform: translate(15px, 30px);
									            transform: translate(15px, 30px); }
									  66% {
									    -webkit-transform: translate(-15px, 30px);
									            transform: translate(-15px, 30px); }
									  100% {
									    -webkit-transform: translate(0, 0);
									            transform: translate(0, 0); } }

									.superpwa-ball-zig-zag {
									  position: relative;
									  -webkit-transform: translate(-15px, -15px);
									          transform: translate(-15px, -15px); }
									  .superpwa-ball-zig-zag > div {
									    background-color: {{selected_color}};
									    width: 15px;
									    height: 15px;
									    border-radius: 100%;
									    margin: 2px;
									    -webkit-animation-fill-mode: both;
									            animation-fill-mode: both;
									    position: absolute;
									    margin-left: 15px;
									    top: 4px;
									    left: -7px; }
									    .superpwa-ball-zig-zag > div:first-child {
									      -webkit-animation: ball-zig 0.7s 0s infinite linear;
									              animation: ball-zig 0.7s 0s infinite linear; }
									    .superpwa-ball-zig-zag > div:last-child {
									      -webkit-animation: ball-zag 0.7s 0s infinite linear;
									              animation: ball-zag 0.7s 0s infinite linear; }'
					),
				'superpwa-ball-zig-zag-deflect' => array(
					'name' 		 => 'superpwa-ball-zig-zag-deflect',//verticle bar
					'class_name' => 'superpwa-ball-zig-zag-deflect',
					'content_html'=> '<div class="superpwa-ball-zig-zag-deflect"><div></div><div></div></div>',
					'css'		 => '@-webkit-keyframes ball-zig-deflect {
									  17% {
									    -webkit-transform: translate(-15px, -30px);
									            transform: translate(-15px, -30px); }
									  34% {
									    -webkit-transform: translate(15px, -30px);
									            transform: translate(15px, -30px); }
									  50% {
									    -webkit-transform: translate(0, 0);
									            transform: translate(0, 0); }
									  67% {
									    -webkit-transform: translate(15px, -30px);
									            transform: translate(15px, -30px); }
									  84% {
									    -webkit-transform: translate(-15px, -30px);
									            transform: translate(-15px, -30px); }
									  100% {
									    -webkit-transform: translate(0, 0);
									            transform: translate(0, 0); } }

									@keyframes ball-zig-deflect {
									  17% {
									    -webkit-transform: translate(-15px, -30px);
									            transform: translate(-15px, -30px); }
									  34% {
									    -webkit-transform: translate(15px, -30px);
									            transform: translate(15px, -30px); }
									  50% {
									    -webkit-transform: translate(0, 0);
									            transform: translate(0, 0); }
									  67% {
									    -webkit-transform: translate(15px, -30px);
									            transform: translate(15px, -30px); }
									  84% {
									    -webkit-transform: translate(-15px, -30px);
									            transform: translate(-15px, -30px); }
									  100% {
									    -webkit-transform: translate(0, 0);
									            transform: translate(0, 0); } }

									@-webkit-keyframes ball-zag-deflect {
									  17% {
									    -webkit-transform: translate(15px, 30px);
									            transform: translate(15px, 30px); }
									  34% {
									    -webkit-transform: translate(-15px, 30px);
									            transform: translate(-15px, 30px); }
									  50% {
									    -webkit-transform: translate(0, 0);
									            transform: translate(0, 0); }
									  67% {
									    -webkit-transform: translate(-15px, 30px);
									            transform: translate(-15px, 30px); }
									  84% {
									    -webkit-transform: translate(15px, 30px);
									            transform: translate(15px, 30px); }
									  100% {
									    -webkit-transform: translate(0, 0);
									            transform: translate(0, 0); } }

									@keyframes ball-zag-deflect {
									  17% {
									    -webkit-transform: translate(15px, 30px);
									            transform: translate(15px, 30px); }
									  34% {
									    -webkit-transform: translate(-15px, 30px);
									            transform: translate(-15px, 30px); }
									  50% {
									    -webkit-transform: translate(0, 0);
									            transform: translate(0, 0); }
									  67% {
									    -webkit-transform: translate(-15px, 30px);
									            transform: translate(-15px, 30px); }
									  84% {
									    -webkit-transform: translate(15px, 30px);
									            transform: translate(15px, 30px); }
									  100% {
									    -webkit-transform: translate(0, 0);
									            transform: translate(0, 0); } }

									.superpwa-ball-zig-zag-deflect {
									  position: relative;
									  -webkit-transform: translate(-15px, -15px);
									          transform: translate(-15px, -15px); }
									  .superpwa-ball-zig-zag-deflect > div {
									    background-color: {{selected_color}};
									    width: 15px;
									    height: 15px;
									    border-radius: 100%;
									    margin: 2px;
									    -webkit-animation-fill-mode: both;
									            animation-fill-mode: both;
									    position: absolute;
									    margin-left: 15px;
									    top: 4px;
									    left: -7px; }
									    .superpwa-ball-zig-zag-deflect > div:first-child {
									      -webkit-animation: ball-zig-deflect 1.5s 0s infinite linear;
									              animation: ball-zig-deflect 1.5s 0s infinite linear; }
									    .superpwa-ball-zig-zag-deflect > div:last-child {
									      -webkit-animation: ball-zag-deflect 1.5s 0s infinite linear;
									              animation: ball-zag-deflect 1.5s 0s infinite linear; }'
					),
				'superpwa-ball-triangle-path' => array(
					'name' 		 => 'superpwa-ball-triangle-path',//verticle bar
					'class_name' => 'superpwa-ball-triangle-path',
					'content_html'=> '<div class="superpwa-ball-triangle-path"><div></div><div></div><div></div></div>',
					'css'		 => '@-webkit-keyframes superpwa-ball-triangle-path-1 {
									  33% {
									    -webkit-transform: translate(25px, -50px);
									            transform: translate(25px, -50px); }
									  66% {
									    -webkit-transform: translate(50px, 0px);
									            transform: translate(50px, 0px); }
									  100% {
									    -webkit-transform: translate(0px, 0px);
									            transform: translate(0px, 0px); } }

									@keyframes superpwa-ball-triangle-path-1 {
									  33% {
									    -webkit-transform: translate(25px, -50px);
									            transform: translate(25px, -50px); }
									  66% {
									    -webkit-transform: translate(50px, 0px);
									            transform: translate(50px, 0px); }
									  100% {
									    -webkit-transform: translate(0px, 0px);
									            transform: translate(0px, 0px); } }

									@-webkit-keyframes superpwa-ball-triangle-path-2 {
									  33% {
									    -webkit-transform: translate(25px, 50px);
									            transform: translate(25px, 50px); }
									  66% {
									    -webkit-transform: translate(-25px, 50px);
									            transform: translate(-25px, 50px); }
									  100% {
									    -webkit-transform: translate(0px, 0px);
									            transform: translate(0px, 0px); } }

									@keyframes superpwa-ball-triangle-path-2 {
									  33% {
									    -webkit-transform: translate(25px, 50px);
									            transform: translate(25px, 50px); }
									  66% {
									    -webkit-transform: translate(-25px, 50px);
									            transform: translate(-25px, 50px); }
									  100% {
									    -webkit-transform: translate(0px, 0px);
									            transform: translate(0px, 0px); } }

									@-webkit-keyframes superpwa-ball-triangle-path-3 {
									  33% {
									    -webkit-transform: translate(-50px, 0px);
									            transform: translate(-50px, 0px); }
									  66% {
									    -webkit-transform: translate(-25px, -50px);
									            transform: translate(-25px, -50px); }
									  100% {
									    -webkit-transform: translate(0px, 0px);
									            transform: translate(0px, 0px); } }

									@keyframes superpwa-ball-triangle-path-3 {
									  33% {
									    -webkit-transform: translate(-50px, 0px);
									            transform: translate(-50px, 0px); }
									  66% {
									    -webkit-transform: translate(-25px, -50px);
									            transform: translate(-25px, -50px); }
									  100% {
									    -webkit-transform: translate(0px, 0px);
									            transform: translate(0px, 0px); } }

									.superpwa-ball-triangle-path {
									  position: relative;
									  -webkit-transform: translate(-29.994px, -37.50938px);
									          transform: translate(-29.994px, -37.50938px); }
									  .superpwa-ball-triangle-path > div:nth-child(1) {
									    -webkit-animation-name: superpwa-ball-triangle-path-1;
									            animation-name: superpwa-ball-triangle-path-1;
									    -webkit-animation-delay: 0;
									            animation-delay: 0;
									    -webkit-animation-duration: 2s;
									            animation-duration: 2s;
									    -webkit-animation-timing-function: ease-in-out;
									            animation-timing-function: ease-in-out;
									    -webkit-animation-iteration-count: infinite;
									            animation-iteration-count: infinite; }
									  .superpwa-ball-triangle-path > div:nth-child(2) {
									    -webkit-animation-name: superpwa-ball-triangle-path-2;
									            animation-name: superpwa-ball-triangle-path-2;
									    -webkit-animation-delay: 0;
									            animation-delay: 0;
									    -webkit-animation-duration: 2s;
									            animation-duration: 2s;
									    -webkit-animation-timing-function: ease-in-out;
									            animation-timing-function: ease-in-out;
									    -webkit-animation-iteration-count: infinite;
									            animation-iteration-count: infinite; }
									  .superpwa-ball-triangle-path > div:nth-child(3) {
									    -webkit-animation-name: superpwa-ball-triangle-path-3;
									            animation-name: superpwa-ball-triangle-path-3;
									    -webkit-animation-delay: 0;
									            animation-delay: 0;
									    -webkit-animation-duration: 2s;
									            animation-duration: 2s;
									    -webkit-animation-timing-function: ease-in-out;
									            animation-timing-function: ease-in-out;
									    -webkit-animation-iteration-count: infinite;
									            animation-iteration-count: infinite; }
									  .superpwa-ball-triangle-path > div {
									    -webkit-animation-fill-mode: both;
									            animation-fill-mode: both;
									    position: absolute;
									    width: 10px;
									    height: 10px;
									    border-radius: 100%;
									    border: 1px solid {{selected_color}}; }
									    .superpwa-ball-triangle-path > div:nth-of-type(1) {
									      top: 50px; }
									    .superpwa-ball-triangle-path > div:nth-of-type(2) {
									      left: 25px; }
									    .superpwa-ball-triangle-path > div:nth-of-type(3) {
									      top: 50px;
									      left: 50px; }
'
					),
				'superpwa-ball-scale' => array(
					'name' 		 => 'superpwa-ball-scale',//verticle bar
					'class_name' => 'superpwa-ball-scale',
					'content_html'=> '<div class="superpwa-ball-scale"><div></div></div>',
					'css'		 => '@-webkit-keyframes superpwa-ball-scale {
									  0% {
									    -webkit-transform: scale(0);
									            transform: scale(0); }
									  100% {
									    -webkit-transform: scale(1);
									            transform: scale(1);
									    opacity: 0; } }

									@keyframes superpwa-ball-scale {
									  0% {
									    -webkit-transform: scale(0);
									            transform: scale(0); }
									  100% {
									    -webkit-transform: scale(1);
									            transform: scale(1);
									    opacity: 0; } }
									
									.superpwa-ball-scale > div {
									  background-color: {{selected_color}};
									  width: 15px;
									  height: 15px;
									  border-radius: 100%;
									  margin: 2px;
									  -webkit-animation-fill-mode: both;
									          animation-fill-mode: both;
									  display: inline-block;
									  height: 60px;
									  width: 60px;
									  -webkit-animation: superpwa-ball-scale 1s 0s ease-in-out infinite;
									          animation: superpwa-ball-scale 1s 0s ease-in-out infinite; }
'
					),
				'superpwa-line-scale' => array(
					'name' 		 => 'superpwa-line-scale',//verticle bar
					'class_name' => 'superpwa-line-scale',
					'content_html'=> '<div class="superpwa-line-scale"><div></div><div></div><div></div><div></div><div></div></div>',
					'css'		 => '@-webkit-keyframes superpwa-line-scale {
										  0% {
										    -webkit-transform: scaley(1);
										            transform: scaley(1); }
										  50% {
										    -webkit-transform: scaley(0.4);
										            transform: scaley(0.4); }
										  100% {
										    -webkit-transform: scaley(1);
										            transform: scaley(1); } }
										@keyframes superpwa-line-scale {
										  0% {
										    -webkit-transform: scaley(1);
										            transform: scaley(1); }
										  50% {
										    -webkit-transform: scaley(0.4);
										            transform: scaley(0.4); }
										  100% {
										    -webkit-transform: scaley(1);
										            transform: scaley(1); } }

										.superpwa-line-scale > div:nth-child(1) {
										  -webkit-animation: superpwa-line-scale 1s -0.4s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08);
										          animation: superpwa-line-scale 1s -0.4s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08); }

										.superpwa-line-scale > div:nth-child(2) {
										  -webkit-animation: superpwa-line-scale 1s -0.3s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08);
										          animation: superpwa-line-scale 1s -0.3s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08); }

										.superpwa-line-scale > div:nth-child(3) {
										  -webkit-animation: superpwa-line-scale 1s -0.2s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08);
										          animation: superpwa-line-scale 1s -0.2s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08); }

										.superpwa-line-scale > div:nth-child(4) {
										  -webkit-animation: superpwa-line-scale 1s -0.1s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08);
										          animation: superpwa-line-scale 1s -0.1s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08); }

										.superpwa-line-scale > div:nth-child(5) {
										  -webkit-animation: superpwa-line-scale 1s 0s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08);
										          animation: superpwa-line-scale 1s 0s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08); }

										.superpwa-line-scale > div {
										  background-color: {{selected_color}};
										  width: 4px;
										  height: 35px;
										  border-radius: 2px;
										  margin: 2px;
										  -webkit-animation-fill-mode: both;
										          animation-fill-mode: both;
										  display: inline-block; }'
					),
				'superpwa-line-scale-party' => array(
					'name' 		 => 'superpwa-line-scale-party',//verticle bar
					'class_name' => 'superpwa-line-scale-party',
					'content_html'=> '<div class="superpwa-line-scale-party"><div></div><div></div><div></div><div></div></div>',
					'css'		 => '@-webkit-keyframes superpwa-line-scale-party {
									  0% {
									    -webkit-transform: scale(1);
									            transform: scale(1); }
									  50% {
									    -webkit-transform: scale(0.5);
									            transform: scale(0.5); }
									  100% {
									    -webkit-transform: scale(1);
									            transform: scale(1); } }

									@keyframes superpwa-line-scale-party {
									  0% {
									    -webkit-transform: scale(1);
									            transform: scale(1); }
									  50% {
									    -webkit-transform: scale(0.5);
									            transform: scale(0.5); }
									  100% {
									    -webkit-transform: scale(1);
									            transform: scale(1); } }

									.superpwa-line-scale-party > div:nth-child(1) {
									  -webkit-animation-delay: 0.48s;
									          animation-delay: 0.48s;
									  -webkit-animation-duration: 0.54s;
									          animation-duration: 0.54s; }

									.superpwa-line-scale-party > div:nth-child(2) {
									  -webkit-animation-delay: -0.15s;
									          animation-delay: -0.15s;
									  -webkit-animation-duration: 1.15s;
									          animation-duration: 1.15s; }

									.superpwa-line-scale-party > div:nth-child(3) {
									  -webkit-animation-delay: 0.04s;
									          animation-delay: 0.04s;
									  -webkit-animation-duration: 0.77s;
									          animation-duration: 0.77s; }

									.superpwa-line-scale-party > div:nth-child(4) {
									  -webkit-animation-delay: -0.12s;
									          animation-delay: -0.12s;
									  -webkit-animation-duration: 0.61s;
									          animation-duration: 0.61s; }

									.superpwa-line-scale-party > div {
									  background-color: {{selected_color}};
									  width: 4px;
									  height: 35px;
									  border-radius: 2px;
									  margin: 2px;
									  -webkit-animation-fill-mode: both;
									          animation-fill-mode: both;
									  display: inline-block;
									  -webkit-animation-name: superpwa-line-scale-party;
									          animation-name: superpwa-line-scale-party;
									  -webkit-animation-iteration-count: infinite;
									          animation-iteration-count: infinite;
									  -webkit-animation-delay: 0;
									          animation-delay: 0; }'
					),
				'superpwa-ball-scale-multiple' => array(
					'name' 		 => 'superpwa-ball-scale-multiple',//verticle bar
					'class_name' => 'superpwa-ball-scale-multiple',
					'content_html'=> '<div class="superpwa-ball-scale-multiple"><div></div><div></div><div></div></div>',
					'css'		 => '@-webkit-keyframes superpwa-ball-scale-multiple {
									  0% {
									    -webkit-transform: scale(0);
									            transform: scale(0);
									    opacity: 0; }
									  5% {
									    opacity: 1; }
									  100% {
									    -webkit-transform: scale(1);
									            transform: scale(1);
									    opacity: 0; } }

									@keyframes superpwa-ball-scale-multiple {
									  0% {
									    -webkit-transform: scale(0);
									            transform: scale(0);
									    opacity: 0; }
									  5% {
									    opacity: 1; }
									  100% {
									    -webkit-transform: scale(1);
									            transform: scale(1);
									    opacity: 0; } }

									.superpwa-ball-scale-multiple {
									  position: relative;
									  -webkit-transform: translateY(-30px);
									          transform: translateY(-30px); }
									  .superpwa-ball-scale-multiple > div:nth-child(2) {
									    -webkit-animation-delay: -0.4s;
									            animation-delay: -0.4s; }
									  .superpwa-ball-scale-multiple > div:nth-child(3) {
									    -webkit-animation-delay: -0.2s;
									            animation-delay: -0.2s; }
									  .superpwa-ball-scale-multiple > div {
									    background-color: {{selected_color}};
									    width: 15px;
									    height: 15px;
									    border-radius: 100%;
									    margin: 2px;
									    -webkit-animation-fill-mode: both;
									            animation-fill-mode: both;
									    position: absolute;
									    left: -30px;
									    top: 0px;
									    opacity: 0;
									    margin: 0;
									    width: 60px;
									    height: 60px;
									    -webkit-animation: superpwa-ball-scale-multiple 1s 0s linear infinite;
									            animation: superpwa-ball-scale-multiple 1s 0s linear infinite; }'
					),
				'superpwa-ball-pulse-sync' => array(
					'name' 		 => 'superpwa-ball-pulse-sync',//verticle bar
					'class_name' => 'superpwa-ball-pulse-sync',
					'content_html'=> '<div class="superpwa-ball-pulse-sync"><div></div><div></div><div></div></div>',
					'css'		 => '@-webkit-keyframes superpwa-ball-pulse-sync {
									  33% {
									    -webkit-transform: translateY(10px);
									            transform: translateY(10px); }
									  66% {
									    -webkit-transform: translateY(-10px);
									            transform: translateY(-10px); }
									  100% {
									    -webkit-transform: translateY(0);
									            transform: translateY(0); } }

									@keyframes superpwa-ball-pulse-sync {
									  33% {
									    -webkit-transform: translateY(10px);
									            transform: translateY(10px); }
									  66% {
									    -webkit-transform: translateY(-10px);
									            transform: translateY(-10px); }
									  100% {
									    -webkit-transform: translateY(0);
									            transform: translateY(0); } }

									.superpwa-ball-pulse-sync > div:nth-child(1) {
									  -webkit-animation: superpwa-ball-pulse-sync 0.6s -0.14s infinite ease-in-out;
									          animation: superpwa-ball-pulse-sync 0.6s -0.14s infinite ease-in-out; }

									.superpwa-ball-pulse-sync > div:nth-child(2) {
									  -webkit-animation: superpwa-ball-pulse-sync 0.6s -0.07s infinite ease-in-out;
									          animation: superpwa-ball-pulse-sync 0.6s -0.07s infinite ease-in-out; }

									.superpwa-ball-pulse-sync > div:nth-child(3) {
									  -webkit-animation: superpwa-ball-pulse-sync 0.6s 0s infinite ease-in-out;
									          animation: superpwa-ball-pulse-sync 0.6s 0s infinite ease-in-out; }

									.superpwa-ball-pulse-sync > div {
									  background-color: {{selected_color}};
									  width: 15px;
									  height: 15px;
									  border-radius: 100%;
									  margin: 2px;
									  -webkit-animation-fill-mode: both;
									          animation-fill-mode: both;
									  display: inline-block; }'
					),
				'superpwa-ball-beat' => array(
					'name' 		 => 'superpwa-ball-beat',//verticle bar
					'class_name' => 'superpwa-ball-beat',
					'content_html'=> '<div class="superpwa-ball-beat"><div></div><div></div><div></div></div>',
					'css'		 => '@-webkit-keyframes superpwa-ball-beat {
									  50% {
									    opacity: 0.2;
									    -webkit-transform: scale(0.75);
									            transform: scale(0.75); }
									  100% {
									    opacity: 1;
									    -webkit-transform: scale(1);
									            transform: scale(1); } }

									@keyframes superpwa-ball-beat {
									  50% {
									    opacity: 0.2;
									    -webkit-transform: scale(0.75);
									            transform: scale(0.75); }
									  100% {
									    opacity: 1;
									    -webkit-transform: scale(1);
									            transform: scale(1); } }

									.superpwa-ball-beat > div {
									  background-color: {{selected_color}};
									  width: 15px;
									  height: 15px;
									  border-radius: 100%;
									  margin: 2px;
									  -webkit-animation-fill-mode: both;
									          animation-fill-mode: both;
									  display: inline-block;
									  -webkit-animation: superpwa-ball-beat 0.7s 0s infinite linear;
									          animation: superpwa-ball-beat 0.7s 0s infinite linear; }
									  .superpwa-ball-beat > div:nth-child(2n-1) {
									    -webkit-animation-delay: -0.35s !important;
									            animation-delay: -0.35s !important; }'
					),
				'superpwa-line-scale-pulse-out' => array(
					'name' 		 => 'superpwa-line-scale-pulse-out',//verticle bar
					'class_name' => 'superpwa-line-scale-pulse-out',
					'content_html'=> '<div class="superpwa-line-scale-pulse-out"><div></div><div></div><div></div><div></div><div></div></div>',
					'css'		 => '@-webkit-keyframes superpwa-line-scale-pulse-out {
									  0% {
									    -webkit-transform: scaley(1);
									            transform: scaley(1); }
									  50% {
									    -webkit-transform: scaley(0.4);
									            transform: scaley(0.4); }
									  100% {
									    -webkit-transform: scaley(1);
									            transform: scaley(1); } }

									@keyframes superpwa-line-scale-pulse-out {
									  0% {
									    -webkit-transform: scaley(1);
									            transform: scaley(1); }
									  50% {
									    -webkit-transform: scaley(0.4);
									            transform: scaley(0.4); }
									  100% {
									    -webkit-transform: scaley(1);
									            transform: scaley(1); } }

									.superpwa-line-scale-pulse-out > div {
									  background-color: {{selected_color}};
									  width: 4px;
									  height: 35px;
									  border-radius: 2px;
									  margin: 2px;
									  -webkit-animation-fill-mode: both;
									          animation-fill-mode: both;
									  display: inline-block;
									  -webkit-animation: superpwa-line-scale-pulse-out 0.9s -0.6s infinite cubic-bezier(0.85, 0.25, 0.37, 0.85);
									          animation: superpwa-line-scale-pulse-out 0.9s -0.6s infinite cubic-bezier(0.85, 0.25, 0.37, 0.85); }
									  .superpwa-line-scale-pulse-out > div:nth-child(2), .superpwa-line-scale-pulse-out > div:nth-child(4) {
									    -webkit-animation-delay: -0.4s !important;
									            animation-delay: -0.4s !important; }
									  .superpwa-line-scale-pulse-out > div:nth-child(1), .superpwa-line-scale-pulse-out > div:nth-child(5) {
									    -webkit-animation-delay: -0.2s !important;
									            animation-delay: -0.2s !important; }'
					),
				/*'pwa-line-scale-pulse-out-rapid' => array(
					'name' 		 => 'pwa-line-scale-pulse-out-rapid',//verticle bar
					'class_name' => 'pwa-line-scale-pulse-out-rapid',
					'content_html'=> '<div class="pwa-line-scale-pulse-out-rapid"><div></div><div></div><div></div><div></div><div></div></div>',
					'css'		 => '@-webkit-keyframes pwa-line-scale-pulse-out-rapid {
									  0% {
									    -webkit-transform: scaley(1);
									            transform: scaley(1); }
									  80% {
									    -webkit-transform: scaley(0.3);
									            transform: scaley(0.3); }
									  90% {
									    -webkit-transform: scaley(1);
									            transform: scaley(1); } }

									@keyframes pwa-line-scale-pulse-out-rapid {
									  0% {
									    -webkit-transform: scaley(1);
									            transform: scaley(1); }
									  80% {
									    -webkit-transform: scaley(0.3);
									            transform: scaley(0.3); }
									  90% {
									    -webkit-transform: scaley(1);
									            transform: scaley(1); } }

									.pwa-line-scale-pulse-out-rapid > div {
									  background-color: {{selected_color}};
									  width: 4px;
									  height: 35px;
									  border-radius: 2px;
									  margin: 2px;
									  -webkit-animation-fill-mode: both;
									          animation-fill-mode: both;
									  display: inline-block;
									  vertical-align: middle;
									  -webkit-animation: pwa-line-scale-pulse-out-rapid 0.9s -0.5s infinite cubic-bezier(0.11, 0.49, 0.38, 0.78);
									          animation: pwa-line-scale-pulse-out-rapid 0.9s -0.5s infinite cubic-bezier(0.11, 0.49, 0.38, 0.78); }
									  .pwa-line-scale-pulse-out-rapid > div:nth-child(2), .pwa-line-scale-pulse-out-rapid > div:nth-child(4) {
									    -webkit-animation-delay: -0.25s !important;
									            animation-delay: -0.25s !important; }
									  .pwa-line-scale-pulse-out-rapid > div:nth-child(1), .pwa-line-scale-pulse-out-rapid > div:nth-child(5) {
									    -webkit-animation-delay: 0s !important;
									            animation-delay: 0s !important; }'
					),*/
				'superpwa-ball-scale-ripple' => array(
					'name' 		 => 'superpwa-ball-scale-ripple',//verticle bar
					'class_name' => 'superpwa-ball-scale-ripple',
					'content_html'=> '<div class="superpwa-ball-scale-ripple"><div></div></div>',
					'css'		 => '@-webkit-keyframes superpwa-ball-scale-ripple {
									  0% {
									    -webkit-transform: scale(0.1);
									            transform: scale(0.1);
									    opacity: 1; }
									  70% {
									    -webkit-transform: scale(1);
									            transform: scale(1);
									    opacity: 0.7; }
									  100% {
									    opacity: 0.0; } }

									@keyframes superpwa-ball-scale-ripple {
									  0% {
									    -webkit-transform: scale(0.1);
									            transform: scale(0.1);
									    opacity: 1; }
									  70% {
									    -webkit-transform: scale(1);
									            transform: scale(1);
									    opacity: 0.7; }
									  100% {
									    opacity: 0.0; } }

									.superpwa-ball-scale-ripple > div {
									  -webkit-animation-fill-mode: both;
									          animation-fill-mode: both;
									  height: 50px;
									  width: 50px;
									  border-radius: 100%;
									  border: 2px solid {{selected_color}};
									  -webkit-animation: superpwa-ball-scale-ripple 1s 0s infinite cubic-bezier(0.21, 0.53, 0.56, 0.8);
									          animation: superpwa-ball-scale-ripple 1s 0s infinite cubic-bezier(0.21, 0.53, 0.56, 0.8); }'
					),
				
			);