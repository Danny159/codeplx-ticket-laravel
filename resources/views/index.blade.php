<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
</head>

<body class="border-top-wide border-primary d-flex flex-column">
    <div class="page page-center">
        <div class="container py-4">
            <div class="row">
                <div class="col-lg-7 col-md-10 col-sm-12 mx-auto mb-3">
                    <h1>Codeplx Support</h1>
                    <div class="text-muted">Sometimes, things go wrong. If you need support with your website or application please use this form and we will get back to you as soon as possible.</div>
                </div>
                <div class="col-lg-7 col-md-10 col-sm-12 mx-auto mb-3">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('codeplx.support.store') }}" method="POST" class="row">
                                @csrf
                                <div class="alertbox">
                                    @if(session()->has('error'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ session('error') }}
                                    </div>
                                    @endif
                                    @if(session()->has('success'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('success') }}
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Your Name</label>
                                    <input type="text" name="name" value="{{ auth()->user()->name ?? old('name') }}" class="form-control" placeholder="your name" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Your Email</label>
                                    <input type="text" name="email" value="{{ auth()->user()->email ?? old('email') }}" class="form-control" placeholder="your email" required>
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Ticket Category</label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="" selected disabled>-- Select Category --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label required">Ticket Subject</label>
                                    <input type="text" name="subject" value="{{ old('subject') }}" class="form-control" placeholder="subject" required>
                                    @error('subject')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label required">Your Message</label>
                                    <textarea name="message" rows="6" class="form-control" placeholder="Let us know how we can help you..." required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3 text-end">
                                    <button type="submit" class="btn btn-secondary">Send message</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-md-10 col-sm-12 mx-auto mb-3 text-center text-muted">
                    <small>&copy; Codeplx {{ now()->year }}. All rights reserved.</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabler Core -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>

</body>
</html>
