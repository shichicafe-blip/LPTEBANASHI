#!/usr/bin/env python3
"""ローカル検証用: マルチスレッド + Range対応の静的サーバ。
原本(nginx/HTTP2)同等に大きな動画でもブロックしないようにする。"""
import sys, os
from http.server import SimpleHTTPRequestHandler, ThreadingHTTPServer

class RangeHandler(SimpleHTTPRequestHandler):
    def send_head(self):
        rng = self.headers.get('Range')
        if not rng:
            return super().send_head()
        path = self.translate_path(self.path)
        try:
            f = open(path, 'rb')
        except OSError:
            self.send_error(404)
            return None
        try:
            size = os.fstat(f.fileno()).st_size
            unit, _, rangespec = rng.partition('=')
            start_s, _, end_s = rangespec.partition('-')
            start = int(start_s) if start_s else 0
            end = int(end_s) if end_s else size - 1
            end = min(end, size - 1)
            length = end - start + 1
            self.send_response(206)
            self.send_header('Content-Type', self.guess_type(path))
            self.send_header('Accept-Ranges', 'bytes')
            self.send_header('Content-Range', f'bytes {start}-{end}/{size}')
            self.send_header('Content-Length', str(length))
            self.end_headers()
            f.seek(start)
            self._remaining = length
            return f
        except Exception:
            f.close()
            raise

    def copyfile(self, source, outputfile):
        rem = getattr(self, '_remaining', None)
        if rem is None:
            return super().copyfile(source, outputfile)
        while rem > 0:
            chunk = source.read(min(64 * 1024, rem))
            if not chunk:
                break
            try:
                outputfile.write(chunk)
            except (BrokenPipeError, ConnectionResetError):
                break
            rem -= len(chunk)

if __name__ == '__main__':
    port = int(sys.argv[1]) if len(sys.argv) > 1 else 8765
    os.chdir(os.path.dirname(os.path.abspath(__file__)))
    httpd = ThreadingHTTPServer(('127.0.0.1', port), RangeHandler)
    httpd.daemon_threads = True
    print(f'serving on http://127.0.0.1:{port}', flush=True)
    httpd.serve_forever()
