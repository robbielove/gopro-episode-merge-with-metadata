# Gopro episode merge with metadata

## Usage
```shell
php find.php /path/to/gopro/episodes /path/to/output/dir /path/to/store/episode-lists
```
All arguments are optional

## Processing

Only files matching GXCCEEEE.MP4, where C or E is a chapter or episode number, are processed.

Files with consecutive chapter numbers and matching episode numbers are merged. (GX01EEEE, GX02EEEE, GX03EEEE, etc.)

Output files are named EEEE.MP4.

Output files are placed in the output directory. (default is input directory + /output, can be changed by passing the output directory as the second parameter)

Output file timestamps are touched to the timestamp of the first chapter file of the apisode.

## Metadata

The track order of the metadata is preserved.
The SOS metadata is not preserved.

This blog post was helpful: [https://coderunner.io/how-to-compress-gopro-movies-and-keep-metadata/]

sample original `ffmpeg -i`

```shell
ffmpeg -i GX010147.MP4 
ffmpeg version 4.4.1 Copyright (c) 2000-2021 the FFmpeg developers
  built with Apple clang version 13.0.0 (clang-1300.0.29.3)
  configuration: --prefix=/usr/local/Cellar/ffmpeg/4.4.1_3 --enable-shared --enable-pthreads --enable-version3 --cc=clang --host-cflags= --host-ldflags= --enable-ffplay --enable-gnutls --enable-gpl --enable-libaom --enable-libbluray --enable-libdav1d --enable-libmp3lame --enable-libopus --enable-librav1e --enable-librist --enable-librubberband --enable-libsnappy --enable-libsrt --enable-libtesseract --enable-libtheora --enable-libvidstab --enable-libvmaf --enable-libvorbis --enable-libvpx --enable-libwebp --enable-libx264 --enable-libx265 --enable-libxml2 --enable-libxvid --enable-lzma --enable-libfontconfig --enable-libfreetype --enable-frei0r --enable-libass --enable-libopencore-amrnb --enable-libopencore-amrwb --enable-libopenjpeg --enable-libspeex --enable-libsoxr --enable-libzmq --enable-libzimg --disable-libjack --disable-indev=jack --enable-avresample --enable-videotoolbox
  libavutil      56. 70.100 / 56. 70.100
  libavcodec     58.134.100 / 58.134.100
  libavformat    58. 76.100 / 58. 76.100
  libavdevice    58. 13.100 / 58. 13.100
  libavfilter     7.110.100 /  7.110.100
  libavresample   4.  0.  0 /  4.  0.  0
  libswscale      5.  9.100 /  5.  9.100
  libswresample   3.  9.100 /  3.  9.100
  libpostproc    55.  9.100 / 55.  9.100
Input #0, mov,mp4,m4a,3gp,3g2,mj2, from '/Volumes/10/Video Assets/HERO 9/GX010147.MP4':
  Metadata:
    major_brand     : mp41
    minor_version   : 538120216
    compatible_brands: mp41
    creation_time   : 2021-02-17T07:43:19.000000Z
    firmware        : HD9.01.01.50.00
  Duration: 00:08:49.56, start: 0.000000, bitrate: 60476 kb/s
  Stream #0:0(eng): Video: hevc (Main) (hvc1 / 0x31637668), yuvj420p(pc, bt709), 3840x2160 [SAR 1:1 DAR 16:9], 59935 kb/s, 29.97 fps, 29.97 tbr, 90k tbn, 29.97 tbc (default)
    Metadata:
      creation_time   : 2021-02-17T07:43:19.000000Z
      handler_name    : GoPro H.265
      vendor_id       : [0][0][0][0]
      encoder         : GoPro H.265 encoder
      timecode        : 07:42:51:25
  Stream #0:1(eng): Audio: aac (LC) (mp4a / 0x6134706D), 48000 Hz, stereo, fltp, 189 kb/s (default)
    Metadata:
      creation_time   : 2021-02-17T07:43:19.000000Z
      handler_name    : GoPro AAC  
      vendor_id       : [0][0][0][0]
      timecode        : 07:42:51:25
  Stream #0:2(eng): Data: none (tmcd / 0x64636D74) (default)
    Metadata:
      creation_time   : 2021-02-17T07:43:19.000000Z
      handler_name    : GoPro TCD  
      timecode        : 07:42:51:25
  Stream #0:3(eng): Data: bin_data (gpmd / 0x646D7067), 54 kb/s (default)
    Metadata:
      creation_time   : 2021-02-17T07:43:19.000000Z
      handler_name    : GoPro MET  
  Stream #0:4(eng): Data: none (fdsc / 0x63736466), 9 kb/s (default)
    Metadata:
      creation_time   : 2021-02-17T07:43:19.000000Z
      handler_name    : GoPro SOS  
```

sample output `ffmpeg -i`

```shell
ffmpeg -i output/0147.MP4 
ffmpeg version 4.4.1 Copyright (c) 2000-2021 the FFmpeg developers
  built with Apple clang version 13.0.0 (clang-1300.0.29.3)
  configuration: --prefix=/usr/local/Cellar/ffmpeg/4.4.1_3 --enable-shared --enable-pthreads --enable-version3 --cc=clang --host-cflags= --host-ldflags= --enable-ffplay --enable-gnutls --enable-gpl --enable-libaom --enable-libbluray --enable-libdav1d --enable-libmp3lame --enable-libopus --enable-librav1e --enable-librist --enable-librubberband --enable-libsnappy --enable-libsrt --enable-libtesseract --enable-libtheora --enable-libvidstab --enable-libvmaf --enable-libvorbis --enable-libvpx --enable-libwebp --enable-libx264 --enable-libx265 --enable-libxml2 --enable-libxvid --enable-lzma --enable-libfontconfig --enable-libfreetype --enable-frei0r --enable-libass --enable-libopencore-amrnb --enable-libopencore-amrwb --enable-libopenjpeg --enable-libspeex --enable-libsoxr --enable-libzmq --enable-libzimg --disable-libjack --disable-indev=jack --enable-avresample --enable-videotoolbox
  libavutil      56. 70.100 / 56. 70.100
  libavcodec     58.134.100 / 58.134.100
  libavformat    58. 76.100 / 58. 76.100
  libavdevice    58. 13.100 / 58. 13.100
  libavfilter     7.110.100 /  7.110.100
  libavresample   4.  0.  0 /  4.  0.  0
  libswscale      5.  9.100 /  5.  9.100
  libswresample   3.  9.100 /  3.  9.100
  libpostproc    55.  9.100 / 55.  9.100
Input #0, mov,mp4,m4a,3gp,3g2,mj2, from '/Volumes/10/Video Assets/HERO 9/output/0147.MP4':
  Metadata:
    major_brand     : isom
    minor_version   : 512
    compatible_brands: isomiso2mp41
    encoder         : Lavf58.76.100
  Duration: 00:09:38.51, start: 0.000000, bitrate: 60231 kb/s
  Stream #0:0(eng): Video: hevc (Main) (hvc1 / 0x31637668), yuvj420p(pc, bt709), 3840x2160 [SAR 1:1 DAR 16:9], 59924 kb/s, 29.97 fps, 29.97 tbr, 90k tbn, 29.97 tbc (default)
    Metadata:
      handler_name    : GoPro H.265
      vendor_id       : [0][0][0][0]
      timecode        : 07:42:51:25
  Stream #0:1(eng): Audio: aac (LC) (mp4a / 0x6134706D), 48000 Hz, stereo, fltp, 189 kb/s (default)
    Metadata:
      handler_name    : GoPro AAC  
      vendor_id       : [0][0][0][0]
  Stream #0:2(eng): Data: bin_data (gpmd / 0x646D7067), 54 kb/s
    Metadata:
      handler_name    : GoPro MET  
  Stream #0:3(eng): Data: bin_data (gpmd / 0x646D7067), 54 kb/s
    Metadata:
      handler_name    : GoPro MET  
  Stream #0:4(eng): Data: none (tmcd / 0x64636D74)
    Metadata:
      handler_name    : GoPro H.265
      timecode        : 07:42:51:25
```